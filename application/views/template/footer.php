<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<!-- Scripts -->
		<script defer src="<?= base_url('assets/js/bundle.js') ?>"></script>
		
		<!-- DataTables Scripts -->
		<script>
  $(document).ready(function() {
      $('#jasaTable').DataTable({
          dom: 'Bfrtip',
          buttons: [
              {
                  extend: 'copy',
                  className: 'btn btn-sm btn-primary'
              },
              {
                  extend: 'csv',
                  className: 'btn btn-sm btn-primary'
              },
              {
                  extend: 'excel',
                  className: 'btn btn-sm btn-primary'
              },
              {
                  extend: 'pdf',
                  className: 'btn btn-sm btn-primary'
              },
              {
                  extend: 'print',
                  className: 'btn btn-sm btn-primary'
              }
          ],
          language: {
              search: "",
              searchPlaceholder: "Search..."
          },
          pageLength: 10,
          ordering: true,
          responsive: true
      });
  });
		</script>
	</body>
</html>
