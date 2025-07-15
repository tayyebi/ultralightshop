<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Enhanced_Simple_Importer {
    private $upload_dir;
    private $report;
    private $slug_cache;

    public function __construct() {
        if ( function_exists('libxml_disable_entity_loader') ) {
            libxml_disable_entity_loader(true);
        }
        $u = wp_upload_dir();
        $this->upload_dir = "{$u['basedir']}/simple-importer";
        $this->report     = ['total'=>0,'inserted'=>0,'updated'=>0,'errors'=>[]];
        $this->slug_cache = [];

        add_action('admin_menu',   [$this,'add_menu']);
        add_action('admin_init',   [$this,'maybe_create_upload_dir']);
    }

    public function maybe_create_upload_dir() {
        if ( ! file_exists($this->upload_dir) ) {
            wp_mkdir_p($this->upload_dir);
        }
    }

    public function add_menu() {
        add_menu_page(
            'Enhanced Importer',
            'Enhanced Importer',
            'manage_options',
            'enhanced-importer',
            [$this,'render_page'],
            'dashicons-upload',
            80
        );
    }

    public function render_page() {
        echo '<div class="wrap"><h1>Enhanced Simple Importer</h1>';

        if ( isset($_POST['si_import_execute']) 
             && check_admin_referer('si_handle_mapping','si_nonce') ) {
            $this->do_import();
            $this->show_report();
            echo '</div>';
            return;
        }

        if ( isset($_POST['si_step'],$_POST['si_file'],$_POST['si_type'])
             && $_POST['si_step']==='preview'
             && check_admin_referer('si_handle_mapping','si_nonce') ) {
            $file = sanitize_text_field($_POST['si_file']);
            $type = sanitize_text_field($_POST['si_type']);
            $this->show_mapping($file,$type);
            echo '</div>';
            return;
        }

        if ( isset($_POST['si_step'])
             && $_POST['si_step']==='upload'
             && check_admin_referer('si_handle_upload','si_nonce') ) {
            $this->handle_upload();
            echo '</div>';
            return;
        }

        $this->upload_form();
        echo '</div>';
    }

    private function upload_form() {
        echo '<form method="post" enctype="multipart/form-data" action="'
           . esc_url(admin_url('admin.php?page=enhanced-importer'))
           . '">';
        wp_nonce_field('si_handle_upload','si_nonce');
        echo '<input type="hidden" name="si_step" value="upload">';
        echo '<table class="form-table"><tr>'
           . '<th><label for="si_file">XML or CSV File</label></th>'
           . '<td><input type="file" id="si_file" name="si_file" required></td>'
           . '</tr><tr>'
           . '<th><label for="si_post_type">Post Type</label></th>'
           . '<td><select name="si_post_type">';
        foreach ( get_post_types(['public'=>true],'objects') as $pt ) {
            echo '<option value="'.esc_attr($pt->name).'">'
               . esc_html($pt->label)
               . '</option>';
        }
        echo '</select></td></tr></table>';
        submit_button('Upload & Next');
        echo '</form>';
    }

    private function handle_upload() {
        if ( empty($_FILES['si_file']['tmp_name']) ) {
            echo '<div class="error"><p>No file uploaded.</p></div>';
            return;
        }
        $tmp  = $_FILES['si_file']['tmp_name'];
        $name = sanitize_file_name($_FILES['si_file']['name']);
        $dst  = "{$this->upload_dir}/".time()."_{$name}";
        move_uploaded_file($tmp,$dst);
        $ext  = strtolower(pathinfo($dst,PATHINFO_EXTENSION));
        $type = $ext==='xml'?'xml':'csv';
        $this->show_mapping($dst,$type);
    }

    private function show_mapping($path,$type) {
        if ( ! file_exists($path) ) {
            echo '<div class="error"><p>Invalid file path.</p></div>';
            return;
        }
        $post_type = sanitize_text_field($_POST['si_post_type']??'');
        echo '<form method="post" action="'
           . esc_url(admin_url('admin.php?page=enhanced-importer'))
           . '">';
        wp_nonce_field('si_handle_mapping','si_nonce');
        echo '<input type="hidden" name="si_step" value="preview">'
           . '<input type="hidden" name="si_file" value="'.esc_attr($path).'">'
           . '<input type="hidden" name="si_type" value="'.esc_attr($type).'">'
           . '<input type="hidden" name="si_post_type" value="'.esc_attr($post_type).'">';
        if ( $type==='xml' ) {
            $this->show_xml_mapping($path);
        } else {
            $this->show_csv_mapping($path);
        }
        echo '</form>';
    }

    private function get_mapping_options() {
        global $wpdb;
        $core = [
            'post_title'=>'post_title',
            'post_content'=>'post_content',
            'post_excerpt'=>'post_excerpt',
            'post_date'=>'post_date',
            'post_status'=>'post_status',
            'post_author'=>'post_author'
        ];
        $meta_keys = $wpdb->get_col("
            SELECT DISTINCT meta_key
            FROM {$wpdb->postmeta}
            WHERE meta_key NOT LIKE '\\_%'
            ORDER BY meta_key ASC
        ");
        return compact('core','meta_keys');
    }

    private function show_xml_mapping($path) {
        $xml = @simplexml_load_file($path);
        if ( !$xml ) { echo '<div class="error"><p>Invalid XML file.</p></div>'; return; }
        $roots=[]; foreach($xml->children() as $n) $roots[]=$n->getName();
        $roots=array_unique($roots);
        $chosen=$_POST['si_root']??reset($roots);
        $records=[];
        foreach($xml->children() as $n) {
            if($n->getName()!==$chosen) continue;
            $records[]=(array)$n;
            if(count($records)>=3) break;
        }
        echo '<h2>Map XML Fields</h2>'
           . '<table class="form-table"><tr><th>Select root node</th><td><select name="si_root">';
        foreach($roots as $r) {
            printf('<option value="%1$s"%2$s>%1$s</option>',
                esc_attr($r), selected($r,$chosen,false)
            );
        }
        echo '</select></td></tr></table>'
           . '<h3>Sample Preview</h3>';
        if(empty($records)) {
            echo '<p>No records under “'.esc_html($chosen).'”.</p>';
        } else {
            echo '<table class="widefat"><thead><tr>';
            foreach(array_keys($records[0]) as $c) echo '<th>'.esc_html($c).'</th>';
            echo '</tr></thead><tbody>';
            foreach($records as $row) {
                echo '<tr>';
                foreach($row as $v) echo '<td>'.esc_html((string)$v).'</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        $opts    = $this->get_mapping_options();
        $targets = array_merge(
            $opts['core'],
            array_combine($opts['meta_keys'],$opts['meta_keys'])
        );
        echo '<h3>Map to WP Fields</h3>'
           . '<table class="widefat"><thead><tr><th>WP Field / Meta Key</th><th>Source Field</th></tr></thead><tbody>';
        foreach($targets as $key=>$label) {
            echo '<tr><td>'.esc_html($label).'</td><td><select name="map['.esc_attr($key).']">'
               . '<option value="">— none —</option>';
            foreach(array_keys($records[0]) as $src) {
                printf('<option value="%1$s"%2$s>%1$s</option>',
                    esc_attr($src),
                    selected($_POST['map'][$key]??'',$src,false)
                );
            }
            echo '</select></td></tr>';
        }
        echo '</tbody></table>'
           . '<p><label>Use source for slug: <select name="si_slug_field">'
           . '<option value="">— auto —</option>';
        foreach(array_keys($records[0]) as $f) {
            printf('<option value="%1$s">%1$s</option>',esc_attr($f));
        }
        echo '</select></label></p>'
           . '<p class="submit">'
           . '<button type="submit" class="button">Preview</button> '
           . '<button type="submit" name="si_import_execute" value="1" class="button button-primary">Run Import</button>'
           . '</p>';
    }
    private function show_csv_mapping($path) {
        $fh = @fopen($path,'r');
        if ( !$fh ) { echo '<div class="error"><p>Could not open CSV.</p></div>'; return; }
        $hdr = fgetcsv($fh)?:[];
        $prv = [];
        for ($i=0; $i<3 && ($row=fgetcsv($fh)); $i++) {
            $prv[] = array_slice(array_pad($row, count($hdr), ''), 0, count($hdr));
        }
        fclose($fh);
        echo '<h2>Map CSV Columns</h2><h3>Sample Preview</h3>';
        if ( empty($hdr) ) {
            echo '<p>Invalid CSV.</p>';
        } else {
            echo '<table class="widefat"><thead><tr>';
            foreach ($hdr as $col) echo '<th>'.esc_html($col).'</th>';
            echo '</tr></thead><tbody>';
            foreach ($prv as $row) {
                echo '<tr>';
                foreach ($row as $c) echo '<td>'.esc_html($c).'</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        $opts    = $this->get_mapping_options();
        $targets = array_merge(
            $opts['core'],
            array_combine($opts['meta_keys'],$opts['meta_keys'])
        );
        echo '<h3>Map to WP Fields</h3>'
           . '<table class="widefat"><thead><tr><th>WP Field / Meta Key</th><th>Source Column</th></tr></thead><tbody>';
        foreach ($targets as $key=>$label) {
            echo '<tr><td>'.esc_html($label).'</td><td><select name="map['.esc_attr($key).']">'
               . '<option value="">— none —</option>';
            foreach ($hdr as $src) {
                printf('<option value="%1$s"%2$s>%1$s</option>',
                    esc_attr($src),
                    selected($_POST['map'][$key]??'',$src,false)
                );
            }
            echo '</select></td></tr>';
        }
        echo '</tbody></table>'
           . '<p><label>Use column for slug: <select name="si_slug_field">'
           . '<option value="">— auto —</option>';
        foreach ($hdr as $h) {
            printf('<option value="%1$s">%1$s</option>',esc_attr($h));
        }
        echo '</select></label></p>'
           . '<p class="submit">'
           . '<button type="submit" class="button">Preview</button> '
           . '<button type="submit" name="si_import_execute" value="1" class="button button-primary">Run Import</button>'
           . '</p>';
    }

    private function do_import() {
        $file      = sanitize_text_field($_POST['si_file']);
        $type      = sanitize_text_field($_POST['si_type']);
        $post_type = sanitize_text_field($_POST['si_post_type']);
        $map       = array_filter((array)$_POST['map'],'strlen');
        $slug_src  = sanitize_text_field($_POST['si_slug_field']);
        $root      = sanitize_text_field($_POST['si_root']??'');

        if ($type==='xml') {
            $xml = @simplexml_load_file($file);
            foreach ($xml->children() as $n) {
                if ($root && $n->getName()!==$root) continue;
                $this->report['total']++;
                $this->upsert_record((array)$n,$map,$post_type,$slug_src);
            }
        } else {
            if (($fh=@fopen($file,'r'))!==false) {
                $hdr = fgetcsv($fh);
                while (($row=fgetcsv($fh))!==false) {
                    $this->report['total']++;
                    $rec = array_combine($hdr, array_pad($row, count($hdr), ''))?:[];
                    $this->upsert_record($rec,$map,$post_type,$slug_src);
                }
                fclose($fh);
            }
        }
    }

    private function upsert_record($record,$map,$post_type,$slug_src) {
        $slug_raw = $slug_src && !empty($record[$slug_src]) ? (string)$record[$slug_src] : '';
        $slug     = $slug_raw ? sanitize_title($slug_raw,'','db') : '';
        $key      = "{$post_type}|{$slug}";
        $existing = $slug && isset($this->slug_cache[$key]) ? $this->slug_cache[$key] : 0;

        if ($slug && !$existing) {
            $found = get_posts([
                'name'=>$slug,
                'post_type'=>$post_type,
                'post_status'=>'any',
                'numberposts'=>1,
                'fields'=>'ids'
            ]);
            if (!empty($found)) {
                $existing = $found[0];
                $this->slug_cache[$key] = $existing;
            }
        }

        $post_data = ['post_type'=>$post_type,'post_status'=>'publish'];
        if ($existing) $post_data['ID'] = $existing;
        if ($slug)     $post_data['post_name'] = $slug;

        $meta_fields = [];
foreach ( $map as $target => $source ) {
    $value = isset( $record[ $source ] ) ? (string) $record[ $source ] : '';
    if ( in_array( $target, [ 'post_title', 'post_content', 'post_excerpt', 'post_date', 'post_status', 'post_author' ], true ) ) {
        $post_data[ $target ] = $value;
    } else {
        $meta_fields[ $target ] = $value;
    }
}
        if ( empty($post_data['post_title']) && empty($post_data['post_content']) && empty($post_data['post_excerpt']) ) {
            $post_data['post_title'] = $slug?:('Imported '.date('YmdHis'));
        }

        $pid = wp_insert_post($post_data, true);
        if ( is_wp_error($pid) ) {
            $this->report['errors'][] = $pid->get_error_message();
            return;
        }

        foreach ($meta_fields as $mk=>$mv) {
            update_post_meta($pid, $mk, $mv);
        }

        if ($existing) {
            $this->report['updated']++;
        } else {
            $this->report['inserted']++;
            if ($slug) $this->slug_cache[$key] = $pid;
        }
    }

    private function show_report() {
        echo '<h2>Import Report</h2>'
           . '<table class="widefat fixed"><thead><tr>'
           . '<th>Total</th><th>Inserted</th><th>Updated</th><th>Errors</th>'
           . '</tr></thead><tbody>';
        printf(
            '<tr><td>%d</td><td>%d</td><td>%d</td><td>%d</td></tr>',
            $this->report['total'],
            $this->report['inserted'],
            $this->report['updated'],
            count($this->report['errors'])
        );
        echo '</tbody></table>';
        if (!empty($this->report['errors'])) {
            echo '<h3>Errors</h3><ul>';
            foreach ($this->report['errors'] as $e) {
                echo '<li>'.esc_html($e).'</li>';
            }
            echo '</ul>';
        }
    }
}

new Enhanced_Simple_Importer();

