<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= isset($title) ? $title : 'Dashboard' ?> - Sistem Tanda Tangan Digital</title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
  </head>
  <body
    x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
    <div
      x-show="loaded"
      x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
      class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
    >
      <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
      ></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      <!-- ===== Sidebar Start ===== -->
      <aside
        :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
        class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
      >
        <!-- SIDEBAR HEADER -->
        <div
          :class="sidebarToggle ? 'justify-center' : 'justify-between'"
          class="flex items-center gap-2 pt-8 sidebar-header pb-7"
        >
          <a href="<?= base_url('admin/dashboard') ?>">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
              <img class="dark:hidden" src="<?= base_url('assets/images/logo/logo.svg') ?>" alt="Logo" />
              <img
                class="hidden dark:block"
                src="<?= base_url('assets/images/logo/logo-dark.svg') ?>"
                alt="Logo"
              />
            </span>

            <img
              class="logo-icon"
              :class="sidebarToggle ? 'lg:block' : 'hidden'"
              src="<?= base_url('assets/images/logo/logo-icon.svg') ?>"
              alt="Logo"
            />
          </a>

          <button
            class="sidebar-toggler hidden lg:block"
            @click.prevent="sidebarToggle = !sidebarToggle"
          >
            <svg
              class="h-6 w-6 fill-current"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M14.2929 5.29289C14.6834 4.90237 15.3166 4.90237 15.7071 5.29289L21.7071 11.2929C22.0976 11.6834 22.0976 12.3166 21.7071 12.7071L15.7071 18.7071C15.3166 19.0976 14.6834 19.0976 14.2929 18.7071C13.9024 18.3166 13.9024 17.6834 14.2929 17.2929L18.5858 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H18.5858L14.2929 6.70711C13.9024 6.31658 13.9024 5.68342 14.2929 5.29289Z"
                fill=""
              />
            </svg>
          </button>
        </div>
        <!-- SIDEBAR HEADER -->

        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
          <!-- Sidebar Menu -->
          <nav class="pb-4 pt-2 px-0" :class="sidebarToggle ? 'lg:px-2' : 'lg:px-5'">
            <div>
              <h3 class="mb-4 ml-4 text-sm font-semibold text-gray-500 dark:text-gray-400" :class="sidebarToggle ? 'lg:hidden' : ''">
                MENU
              </h3>

              <ul class="mb-6 flex flex-col gap-1.5">
                <!-- Dashboard -->
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    href="<?= base_url('admin/dashboard') ?>"
                  >
                    <svg
                      class="h-5 w-5 fill-current"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M3 4C3 3.44772 3.44772 3 4 3H7C7.55228 3 8 3.44772 8 4V9C8 9.55228 7.55228 10 7 10H4C3.44772 10 3 9.55228 3 9V4ZM5 5V8H6V5H5Z"
                        fill=""
                      />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M3 12C3 11.4477 3.44772 11 4 11H7C7.55228 11 8 11.4477 8 12V16C8 16.5523 7.55228 17 7 17H4C3.44772 17 3 16.5523 3 16V12ZM5 13V15H6V13H5Z"
                        fill=""
                      />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M11 4C11 3.44772 11.4477 3 12 3H15C15.5523 3 16 3.44772 16 4V7C16 7.55228 15.5523 8 15 8H12C11.4477 8 11 7.55228 11 7V4ZM13 5V6H14V5H13Z"
                        fill=""
                      />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M11 10C11 9.44772 11.4477 9 12 9H15C15.5523 9 16 9.44772 16 10V16C16 16.5523 15.5523 17 15 17H12C11.4477 17 11 16.5523 11 16V10ZM13 11V15H14V11H13Z"
                        fill=""
                      />
                    </svg>
                    <span :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
                  </a>
                </li>
                <!-- Kelola Pegawai -->
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    href="<?= base_url('admin/users') ?>"
                  >
                    <svg
                      class="h-5 w-5 fill-current"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M10 9C11.6569 9 13 7.65685 13 6C13 4.34315 11.6569 3 10 3C8.34315 3 7 4.34315 7 6C7 7.65685 8.34315 9 10 9Z"
                        fill=""
                      />
                      <path
                        d="M3 18C3 14.134 6.13401 11 10 11C13.866 11 17 14.134 17 18H3Z"
                        fill=""
                      />
                    </svg>
                    <span :class="sidebarToggle ? 'lg:hidden' : ''">Kelola Pegawai</span>
                  </a>
                </li>
                <!-- Data Jasa/Bonus -->
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    href="<?= base_url('admin/jasa') ?>"
                  >
                    <svg
                      class="h-5 w-5 fill-current"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M4 3C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H16C17.1046 17 18 16.1046 18 15V5C18 3.89543 17.1046 3 16 3H4ZM4 5H16V7H4V5ZM4 9H16V15H4V9Z"
                        fill=""
                      />
                    </svg>
                    <span :class="sidebarToggle ? 'lg:hidden' : ''">Data Jasa/Bonus</span>
                  </a>
                </li>
                <!-- Laporan -->
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    href="<?= base_url('admin/laporan') ?>"
                  >
                    <svg
                      class="h-5 w-5 fill-current"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M3 4C3 3.44772 3.44772 3 4 3H9L11 5H16C16.5523 5 17 5.44772 17 6V14C17 14.5523 16.5523 15 16 15H4C3.44772 15 3 14.5523 3 14V4Z"
                        fill=""
                      />
                    </svg>
                    <span :class="sidebarToggle ? 'lg:hidden' : ''">Laporan</span>
                  </a>
                </li>
                <!-- Logout -->
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    href="<?= base_url('auth/logout') ?>"
                  >
                    <svg
                      class="h-5 w-5 fill-current"
                      viewBox="0 0 20 20"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M3 3C3 2.44772 3.44772 2 4 2H12C12.5523 2 13 2.44772 13 3C13 3.55228 12.5523 4 12 4H5V16H12C12.5523 16 13 16.4477 13 17C13 17.5523 12.5523 18 12 18H4C3.44772 18 3 17.5523 3 17V3Z"
                        fill=""
                      />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L10.7071 12.2929C11.0976 12.6834 11.0976 13.3166 10.7071 13.7071L7.70711 16.7071C7.31658 17.0976 6.68342 17.0976 6.29289 16.7071C5.90237 16.3166 5.90237 15.6834 6.29289 15.2929L8.58579 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H8.58579L6.29289 8.70711C5.90237 8.31658 5.90237 7.68342 6.29289 7.29289Z"
                        fill=""
                      />
                    </svg>
                    <span :class="sidebarToggle ? 'lg:hidden' : ''">Logout</span>
                  </a>
                </li>
              </ul>
            </div>
          </nav>
          <!-- Sidebar Menu -->
        </div>
      </aside>
      <!-- ===== Sidebar End ===== -->

      <!-- ===== Content Area Start ===== -->
      <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        <!-- ===== Header Start ===== -->
        <header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-sm dark:bg-gray-900 dark:drop-shadow-none">
          <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-sm md:px-6 2xl:px-11">
            <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
              <!-- Hamburger Toggle BTN -->
              <button
                class="z-99999 block rounded-sm border border-gray-300 bg-white p-1.5 shadow-sm dark:border-gray-800 dark:bg-gray-900 lg:hidden"
                @click.stop="sidebarToggle = !sidebarToggle"
              >
                <span class="relative block h-5.5 w-5.5 cursor-pointer">
                  <span class="absolute right-0 h-full w-full">
                    <span
                      class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-gray-900 delay-[0] duration-200 ease-in-out dark:bg-white"
                      :class="{'!w-full delay-300': !sidebarToggle}"
                    ></span>
                    <span
                      class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-gray-900 delay-150 duration-200 ease-in-out dark:bg-white"
                      :class="{'!w-full delay-400': !sidebarToggle}"
                    ></span>
                    <span
                      class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-gray-900 delay-200 duration-200 ease-in-out dark:bg-white"
                      :class="{'!w-full delay-500': !sidebarToggle}"
                    ></span>
                  </span>
                  <span class="absolute right-0 h-full w-full rotate-45">
                    <span
                      class="absolute left-2.5 top-0 block h-full w-0.5 rounded-sm bg-gray-900 delay-300 duration-200 ease-in-out dark:bg-white"
                      :class="{'!h-0 delay-[0]': !sidebarToggle}"
                    ></span>
                    <span
                      class="delay-400 absolute left-0 top-2.5 block h-0.5 w-full rounded-sm bg-gray-900 duration-200 ease-in-out dark:bg-white"
                      :class="{'!h-0 delay-200': !sidebarToggle}"
                    ></span>
                  </span>
                </span>
              </button>
              <!-- Hamburger Toggle BTN -->
            </div>

            <div class="hidden sm:block">
              <h1 class="text-title-md2 font-semibold text-gray-900 dark:text-white">
                <?= isset($title) ? $title : 'Dashboard' ?>
              </h1>
            </div>

            <div class="flex items-center gap-3 2xsm:gap-7">
              <!-- Dark Mode Toggler -->
              <div>
                <button
                  class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-gray-300 bg-gray-100 text-gray-600 hover:text-gray-900 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 dark:hover:text-white"
                  @click.prevent="darkMode = !darkMode"
                >
                  <svg
                    class="hidden h-4 w-4 fill-current dark:block"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327ZM5.29402 10.0001C5.29402 7.40061 7.40135 5.29327 10.0009 5.29327C12.6004 5.29327 14.7078 7.40061 14.7078 10.0001C14.7078 12.5997 12.6004 14.707 10.0009 14.707C7.40135 14.707 5.29402 12.5997 5.29402 10.0001ZM15.9813 5.08035C16.2742 4.78746 16.2742 4.31258 15.9813 4.01969C15.6884 3.7268 15.2135 3.7268 14.9207 4.01969L14.0368 4.90357C13.7439 5.19647 13.7439 5.67134 14.0368 5.96423C14.3297 6.25713 14.8045 6.25713 15.0974 5.96423L15.9813 5.08035ZM18.4577 10.0001C18.4577 10.4143 18.1219 10.7501 17.7077 10.7501H16.4577C16.0435 10.7501 15.7077 10.4143 15.7077 10.0001C15.7077 9.58592 16.0435 9.25013 16.4577 9.25013H17.7077C18.1219 9.25013 18.4577 9.58592 18.4577 10.0001ZM14.9207 15.9806C15.2135 16.2735 15.6884 16.2735 15.9813 15.9806C16.2742 15.6877 16.2742 15.2128 15.9813 14.9199L15.0974 14.036C14.8045 13.7431 14.3297 13.7431 14.0368 14.036C13.7439 14.3289 13.7439 14.8038 14.0368 15.0967L14.9207 15.9806ZM9.99998 15.7088C10.4142 15.7088 10.75 16.0445 10.75 16.4588V17.7088C10.75 18.123 10.4142 18.4588 9.99998 18.4588C9.58577 18.4588 9.24998 18.123 9.24998 17.7088V16.4588C9.24998 16.0445 9.58577 15.7088 9.99998 15.7088ZM5.96356 15.0972C6.25646 14.8043 6.25646 14.3295 5.96356 14.0366C5.67067 13.7437 5.1958 13.7437 4.9029 14.0366L4.01902 14.9204C3.72613 15.2133 3.72613 15.6882 4.01902 15.9811C4.31191 16.274 4.78679 16.274 5.07968 15.9811L5.96356 15.0972ZM4.29224 10.0001C4.29224 10.4143 3.95645 10.7501 3.54224 10.7501H2.29224C1.87802 10.7501 1.54224 10.4143 1.54224 10.0001C1.54224 9.58592 1.87802 9.25013 2.29224 9.25013H3.54224C3.95645 9.25013 4.29224 9.58592 4.29224 10.0001ZM4.9029 5.9637C5.1958 6.25659 5.67067 6.25659 5.96356 5.9637C6.25646 5.6708 6.25646 5.19593 5.96356 4.90303L5.07968 4.01915C4.78679 3.72626 4.31191 3.72626 4.01902 4.01915C3.72613 4.31204 3.72613 4.78692 4.01902 5.07981L4.9029 5.9637Z"
                      fill=""
                    />
                  </svg>
                  <svg
                    class="h-4 w-4 fill-current dark:hidden"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459ZM12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035ZM16.944 11.4207C15.8869 12.4035 14.4721 13.0035 12.9154 13.0035V14.5035C14.8657 14.5035 16.6418 13.7499 17.9654 12.5193L16.944 11.4207ZM16.7295 11.7789C15.9437 14.7607 13.2277 16.9586 10.0003 16.9586V18.4586C13.9257 18.4586 17.2249 15.7853 18.1799 12.1611L16.7295 11.7789ZM10.0003 16.9586C6.15734 16.9586 3.04199 13.8433 3.04199 10.0003H1.54199C1.54199 14.6717 5.32892 18.4586 10.0003 18.4586V16.9586ZM3.04199 10.0003C3.04199 6.77289 5.23988 4.05695 8.22173 3.27114L7.83948 1.82066C4.21532 2.77574 1.54199 6.07486 1.54199 10.0003H3.04199ZM6.99707 7.08524C6.99707 5.52854 7.5971 4.11366 8.57989 3.05657L7.48132 2.03522C6.25073 3.35885 5.49707 5.13487 5.49707 7.08524H6.99707Z"
                      fill=""
                    />
                  </svg>
                </button>
              </div>
              <!-- Dark Mode Toggler -->

              <!-- User Area -->
              <div class="relative" x-data="{ dropdownOpen: false }">
                <div class="flex items-center gap-4">
                  <span class="hidden text-right lg:block">
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                      <?= $this->session->userdata('nama') ?>
                    </span>
                    <span class="block text-xs">Admin</span>
                  </span>

                  <span class="h-12 w-12 rounded-full">
                    <img src="<?= base_url('assets/images/user/user-03.png') ?>" alt="User" />
                  </span>

                  <button
                    class="flex items-center"
                    @click.prevent="dropdownOpen = ! dropdownOpen"
                  >
                    <svg
                      class="hidden fill-current sm:block"
                      width="12"
                      height="8"
                      viewBox="0 0 12 8"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.410765 0.910734C0.736202 0.585297 1.26384 0.585297 1.58928 0.910734L6.00002 5.32148L10.4108 0.910734C10.7362 0.585297 11.2638 0.585297 11.5893 0.910734C11.9147 1.23617 11.9147 1.76381 11.5893 2.08924L6.58928 7.08924C6.26384 7.41468 5.7362 7.41468 5.41077 7.08924L0.410765 2.08924C0.0853277 1.76381 0.0853277 1.23617 0.410765 0.910734Z"
                        fill=""
                      />
                    </svg>
                  </button>
                </div>

                <!-- Dropdown Start -->
                <div
                  x-show="dropdownOpen"
                  @click.outside="dropdownOpen = false"
                  class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-gray-300 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900"
                >
                  <ul class="flex flex-col gap-5 border-b border-gray-300 px-6 py-7.5 dark:border-gray-800">
                    <li>
                      <a
                        href="<?= base_url('auth/logout') ?>"
                        class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-brand-500 lg:text-base"
                      >
                        <svg
                          class="fill-current"
                          width="22"
                          height="22"
                          viewBox="0 0 22 22"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M15.5375 0.618744H11.6531C10.7594 0.618744 10.0031 1.37499 10.0031 2.26874V4.64062C10.0031 5.05312 10.3469 5.39687 10.7594 5.39687C11.1719 5.39687 11.55 5.05312 11.55 4.64062V2.23437C11.55 2.16562 11.5844 2.13124 11.6531 2.13124H15.5375C16.3625 2.13124 17.0156 2.78437 17.0156 3.60937V18.3562C17.0156 19.1812 16.3625 19.8344 15.5375 19.8344H11.6531C11.5844 19.8344 11.55 19.8 11.55 19.7312V17.3594C11.55 16.9469 11.2062 16.6031 10.7594 16.6031C10.3125 16.6031 10.0031 16.9469 10.0031 17.3594V19.7312C10.0031 20.625 10.7594 21.3812 11.6531 21.3812H15.5375C17.2219 21.3812 18.5625 20.0406 18.5625 18.3562V3.64374C18.5625 1.95937 17.2219 0.618744 15.5375 0.618744Z"
                            fill=""
                          />
                          <path
                            d="M6.05001 11.7563H12.2031C12.6156 11.7563 12.9594 11.4125 12.9594 11C12.9594 10.5875 12.6156 10.2438 12.2031 10.2438H6.08439L8.21564 8.07813C8.52501 7.76875 8.52501 7.2875 8.21564 6.97812C7.90626 6.66875 7.42501 6.66875 7.11564 6.97812L3.67814 10.4156C3.36876 10.725 3.36876 11.2063 3.67814 11.5156L7.11564 14.9531C7.42501 15.2625 7.90626 15.2625 8.21564 14.9531C8.52501 14.6438 8.52501 14.1625 8.21564 13.8531L6.05001 11.7563Z"
                            fill=""
                          />
                        </svg>
                        Log Out
                      </a>
                    </li>
                  </ul>
                </div>
                <!-- Dropdown End -->
              </div>
              <!-- User Area -->
            </div>
          </div>
        </header>
        <!-- ===== Header End ===== -->

        <!-- ===== Main Content Start ===== -->
        <main>
          <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <?= $content ?>
          </div>
        </main>
        <!-- ===== Main Content End ===== -->
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <!-- TailAdmin Compiled Bundle (includes Alpine.js) -->
    <script defer src="<?= base_url('assets/js/bundle.js') ?>"></script>
  </body>
</html>