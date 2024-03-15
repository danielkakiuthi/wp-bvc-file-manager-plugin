<?php if ( ! defined( 'ABSPATH' ) ) { exit; } 
 $shortcodes = fma_shortcode_generator::getShortcodes();
 add_thickbox(); 
 fma_shortcode_generator::shortcode_generator_enqueue_script();
 ?>
<script src="<?php echo plugins_url('shortcode_generator/assets/js/jquery.dataTables.min.js', fmas_file);?>"></script>
<link rel="stylesheet"
    href="<?php echo plugins_url('shortcode_generator/assets/css/jquery.dataTables.min.css', fmas_file);?>">
<div class="wrap afm-shortcodes-listing afm_main_bg">
    <h3>Shortcodes (<?php echo count($shortcodes);?>) <a
            href="<?php echo admin_url('admin.php?page=fma_shortcode_create')?>" class="button button-primary">Create
            Shortcode</a></h3>

    <?php 
/**
 * Deletion Process
 */
fma_shortcode_generator::deleteShortcode();
?>

    <table id="afm_shortcode_list" class="display afm_shortcode_list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Shortcode</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($shortcodes as $shortcode):?>
            <tr>
                <td>#<?php echo $shortcode->id;?></td>

                <td><strong><a href="#TB_inline?&width=550&height=150&inlineId=shortcode_<?php echo $shortcode->id;?>"
                            class="thickbox"
                            title="Shortcode - <?php echo $shortcode->title;?>"><?php echo $shortcode->title;?></a></strong>
                    <p class="afm_code">
                        <code>[advanced_file_manager_front id="<?php echo $shortcode->shortcode_id;?>" title="<?php echo $shortcode->title; ?>"]</code>
                    </p>
                </td>

                <td><?php echo ($shortcode->type == '1') ? 'Logged In' : 'Non Logged In';?></td>

                <td><?php echo ($shortcode->status == '1') ? '<span class="dashicons dashicons-yes-alt active"></span>' : '<span class="dashicons dashicons-dismiss inactive"></span>';?>
                </td>

                <td><?php echo $shortcode->created_at;?></td>

                <td><?php echo $shortcode->updated_at;?></td>

                <td class="afm_actions">

                    <div><a href="#TB_inline?&width=550&height=150&inlineId=shortcode_<?php echo $shortcode->id;?>"
                            class="thickbox afm_view_shortcode"
                            title="Shortcode - <?php echo $shortcode->title;?>"><span
                                class="dashicons dashicons-shortcode"></span></a>

                        <a href="#TB_inline?&width=750&height=300&inlineId=filter_shortcode_<?php echo $shortcode->id;?>"
                            class="thickbox afm_filter_shortcode"
                            title="Code Filter - <?php echo $shortcode->title;?>"><span
                                class="dashicons dashicons-media-code"></span></a>
                    </div>

                    <div><a href="<?php echo admin_url('admin.php?page=fma_shortcode_edit&id='.$shortcode->id.'&shortcodeId='.$shortcode->shortcode_id.'&nonce='.wp_create_nonce($shortcode->shortcode_id));?>"
                            class="afm_edit_shortcode" title="Edit Shortcode"><span
                                class="dashicons dashicons-edit"></span></a>

                        <a href="<?php echo admin_url('admin.php?page=file_manager_advanced_shortcode_generator&action=delete&id='.$shortcode->id.'&shortcodeId='.$shortcode->shortcode_id.'&nonce='.wp_create_nonce('delete_shortcode_'.$shortcode->shortcode_id));?>"
                            class="afm_delete_shortcode"
                            onclick="return confirm('Are you sure want to delete shortcode?')"
                            title="Delete Shortcode"><span class="dashicons dashicons-trash"></span></a>
                    </div>
                </td>
            </tr>

            <div id="shortcode_<?php echo $shortcode->id;?>" style="display:none;">
                <div><strong>Copy given shortcode and paste in your pages/posts.</strong></div>
                <textarea name="moderation_keys" rows="2" cols="50" class="large-text afm_code"
                    readonly>[advanced_file_manager_front id="<?php echo $shortcode->shortcode_id;?>" title="<?php echo $shortcode->title;?>"]</textarea>
                <div><strong>Copy given shortcode and paste in your php files.</strong></div>
                <textarea name="moderation_keys" rows="2" cols="50" class="large-text afm_code"
                    readonly>&lt?php echo do_shortcode('[advanced_file_manager_front id="<?php echo $shortcode->shortcode_id;?>" title="<?php echo $shortcode->title;?>"]');?&gt;</textarea>
            </div>

            <div id="filter_shortcode_<?php echo $shortcode->id;?>" style="display:none;">
                <p>
                    <strong>For Developers: Copy given filter and paste in your theme's functions.php or any of your
                        plugin file and customize required parameters as per your requirement.</strong>
                </p>
                <?php $shortcodeData = maybe_unserialize($shortcode->shortcode_data); ?>
                <textarea name="moderation_keys" rows="10" cols="50" id="moderation_keys"
                    class="large-text code afm_code" readonly>add_filter('afm_shortcode_filter_<?php echo $shortcode->shortcode_id; ?>','afm_shortcode_filter_<?php echo $shortcode->shortcode_id; ?>_callback' );
            function afm_shortcode_filter_<?php echo $shortcode->shortcode_id; ?>_callback($data){
                $data['path'] = '<?php echo $shortcodeData['path'];?>';
                $data['path_type'] = '<?php echo $shortcodeData['path_type'];?>';
                $data['url'] = '<?php isset($shortcodeData['url']) ? $shortcodeData['url'] : '';?>';
                $data['hide'] = '<?php echo $shortcodeData['hide'];?>';
                $data['operations'] = array('archive','download','upload');
                <?php if($shortcode->type == '1') { ?>$data['block_users'] = '<?php echo $shortcodeData['block_users'];?>';
                <?php } ?>$data['view'] = '<?php echo $shortcodeData['view'];?>';
                $data['theme'] = '<?php echo $shortcodeData['theme'];?>';
                $data['lang'] = '<?php echo $shortcodeData['lang'];?>';
                $data['dateformat'] = '<?php echo $shortcodeData['dateformat'];?>';
                $data['hide_path'] = '<?php echo $shortcodeData['path'];?>';
                $data['enable_trash'] = '<?php echo $shortcodeData['enable_trash'];?>';
                $data['height'] = '<?php echo $shortcodeData['height'];?>';
                $data['width'] = '<?php echo $shortcodeData['width'];?>';
                $data['read'] = '<?php echo isset($shortcodeData['read']) ? $shortcodeData['read'] : '' ;?>';
                $data['write'] = '<?php echo isset($shortcodeData['write']) ? $shortcodeData['write'] : '' ;?>';
                $data['display_ui_options'] = array('tool','bartree','path','stat');
                $data['upload_max_size'] = '<?php echo $shortcodeData['upload_max_size'];?>';
                $data['upload_allow'] = '<?php echo $shortcodeData['upload_allow'];?>';
                return $data;
            }</textarea>
                <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Shortcode</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </tfoot>
    </table>
    <script>
    new DataTable('#afm_shortcode_list', {
        info: true,
        ordering: false,
        paging: true,
        language: {
            emptyTable: "No shortcodes created yet.",
            zeroRecords: "No shortcode matched your search."
        }
    });
    </script>
</div>