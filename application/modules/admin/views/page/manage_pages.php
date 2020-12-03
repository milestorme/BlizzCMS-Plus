    <section class="uk-section uk-section-xsmall" data-uk-height-viewport="expand: true">
      <div class="uk-container">
        <div class="uk-grid uk-grid-small uk-margin-small" data-uk-grid>
          <div class="uk-width-expand uk-heading-line">
            <h3 class="uk-h3"><i class="fas fa-file-alt"></i> <?= $this->lang->line('admin_nav_pages'); ?></h3>
          </div>
          <div class="uk-width-auto">
            <a href="<?= base_url('admin/pages/create'); ?>" class="uk-icon-button"><i class="fas fa-pen"></i></a>
          </div>
        </div>
        <div class="uk-card uk-card-default uk-card-body">
          <div class="uk-overflow-auto">
            <table class="uk-table uk-table-middle uk-table-divider uk-table-small">
              <thead>
                <tr>
                  <th class="uk-width-small"><?= $this->lang->line('placeholder_title'); ?></th>
                  <th class="uk-width-medium"><?= $this->lang->line('placeholder_uri'); ?></th>
                  <th class="uk-width-small"><?= $this->lang->line('table_header_date'); ?></th>
                  <th class="uk-width-small uk-text-center"><?= $this->lang->line('table_header_actions'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($pagesList) && !empty($pagesList)): ?>
                <?php foreach($pagesList as $pages): ?>
                <tr>
                  <td><?= $pages->title ?></td>
                  <td><?= base_url('page/').$pages->uri_friendly; ?></td>
                  <td><?= date('Y-m-d', $pages->date); ?></td>
                  <td>
                    <div class="uk-flex uk-flex-left uk-flex-center@m uk-margin-small">
                      <a href="<?= base_url('admin/pages/edit/'.$pages->id); ?>" class="uk-button uk-button-primary uk-margin-small-right"><i class="fas fa-edit"></i></a>
                      <button class="uk-button uk-button-danger" value="<?= $pages->id ?>" id="button_delete<?= $pages->id ?>" onclick="DeletePage(event, this.value)"><i class="fas fa-trash-alt"></i></button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="uk-card-footer">
            <div class="uk-text-right">
              <?php if (isset($pagesList) && is_array($pagesList)) echo $pagination_links; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      function DeletePage(e, value) {
        e.preventDefault();

        $.ajax({
          url:"<?= base_url($lang.'/admin/pages/delete'); ?>",
          method:"POST",
          data:{value},
          dataType:"text",
          beforeSend: function(){
            $.amaran({
              'theme': 'awesome info',
              'content': {
                title: '<?= $this->lang->line('notification_title_info'); ?>',
                message: '<?= $this->lang->line('notification_checking'); ?>',
                info: '',
                icon: 'fas fa-sign-in-alt'
              },
              'delay': 5000,
              'position': 'top right',
              'inEffect': 'slideRight',
              'outEffect': 'slideRight'
            });
          },
          success:function(response){
            if(!response)
              alert(response);

            if (response) {
              $.amaran({
                'theme': 'awesome ok',
                  'content': {
                  title: '<?= $this->lang->line('notification_title_success'); ?>',
                  message: '<?= $this->lang->line('notification_page_deleted'); ?>',
                  info: '',
                  icon: 'fas fa-check-circle'
                },
                'delay': 5000,
                'position': 'top right',
                'inEffect': 'slideRight',
                'outEffect': 'slideRight'
              });
            }
            window.location.replace("<?= base_url('admin/pages'); ?>");
          }
        });
      }
    </script>
