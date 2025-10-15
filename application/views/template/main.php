<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('template/header', isset($title) ? ['title' => $title] : []); ?>
<?php $this->load->view('template/sidebar'); ?>

<!-- Content Area Start -->
<div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
  <!-- Small Device Overlay Start -->
  <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'" class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
  <!-- Small Device Overlay End -->

  <?php $this->load->view('template/topbar'); ?>

  <!-- Main Content Start -->
  <main>
    <div class="p-4 md:p-5">
      <?php if (isset($content)) { echo $content; } else { $this->load->view($view ?? 'welcome_message'); } ?>
    </div>
  </main>
  <!-- Main Content End -->
</div>
<!-- Content Area End -->

<?php $this->load->view('template/footer'); ?>

<!-- Balance Toggle Script -->
<script src="<?= base_url('assets/js/balance-toggle.js') ?>"></script>
