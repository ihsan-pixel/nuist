@extends('layouts.master-without-nav')

@section('title') PPDB NUIST 2025 @endsection

@section('content')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #16a34a 0%, #059669 50%, #0884d8 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.05);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1000px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        animation: slideDown 0.8s ease;
    }

    .hero-section p {
        font-size: 1.3rem;
        margin-bottom: 8px;
        opacity: 0.95;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Info Cards */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin: 50px auto;
        max-width: 1200px;
        padding: 0 20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-top: 5px solid;
        transition: all 0.3s ease;
        text-align: center;
    }

    .info-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .info-card.card-1 {
        border-top-color: #16a34a;
    }

    .info-card.card-2 {
        border-top-color: #0884d8;
    }

    .info-card.card-3 {
        border-top-color: #16a34a;
    }

    .info-card-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .info-card h3 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .info-card p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Schools Section */
    .schools-section {
        background: #f9fafb;
        padding: 50px 20px;
    }

    .schools-header {
        text-align: center;
        margin-bottom: 40px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .schools-header h2 {
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .schools-header p {
        font-size: 1.1rem;
        color: #6b7280;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* School Card */
    .school-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        border-bottom: 4px solid #16a34a;
    }

    .school-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .school-card-header {
        background: linear-gradient(135deg, #16a34a 0%, #0884d8 100%);
        color: white;
        padding: 25px;
        min-height: 80px;
        display: flex;
        align-items: center;
    }

    .school-card-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.4;
    }

    .school-card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .school-year-badge {
        display: inline-block;
        background: #dcfce7;
        color: #166534;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 15px;
        width: fit-content;
    }

    .school-status {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        text-align: center;
        font-size: 0.9rem;
    }

    .status-open {
        background: #dcfce7;
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .status-waiting {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .schedule-info {
        margin: 15px 0;
        padding: 12px;
        background: #f3f4f6;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #374151;
    }

    .schedule-info strong {
        color: #1f2937;
    }

    .school-card-button {
        margin-top: auto;
        background: linear-gradient(135deg, #16a34a 0%, #059669 100%);
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .school-card-button:hover {
        background: linear-gradient(135deg, #15803d 0%, #047857 100%);
        transform: scale(1.02);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #1f2937;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #16a34a 0%, #0884d8 100%);
        color: white;
        padding: 50px 20px;
        margin: 40px 0;
    }

    .cta-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }

    .cta-section h3 {
        font-size: 2rem;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .cta-section > div > p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .cta-button {
        background: white;
        color: #16a34a;
        padding: 14px 28px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: inline-block;
    }

    .cta-button:hover {
        background: #f0fdf4;
        transform: scale(1.05);
    }

    /* FAQ Section */
    .faq-section {
        background: white;
        padding: 50px 20px;
    }

    .faq-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .faq-header h2 {
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .faq-item {
        background: #f9fafb;
        border-radius: 8px;
        padding: 25px;
        border-left: 4px solid #16a34a;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        background: #f3f4f6;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .faq-item h4 {
        font-size: 1.1rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .faq-item p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Info Important Section */
    .info-important {
        background: white;
        border-radius: 12px;
        border-left: 5px solid #16a34a;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 40px auto;
    }

    .info-important h3 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .info-important ul {
        list-style: none;
        padding: 0;
    }

    .info-important li {
        padding: 10px 0;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-important li:last-child {
        border-bottom: none;
    }

    .info-important li::before {
        content: '✓ ';
        color: #16a34a;
        font-weight: 700;
        margin-right: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2.2rem;
        }

        .hero-section p:first-of-type {
            font-size: 1.1rem;
        }

        .schools-header h2 {
            font-size: 1.8rem;
        }

        .info-cards {
            grid-template-columns: 1fr;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .cta-button {
            width: 100%;
        }

        .schools-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            padding: 40px 15px;
        }

        .hero-section h1 {
            font-size: 1.8rem;
        }

        .school-card-header h3 {
            font-size: 1.1rem;
        }
    }
</style>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Play | Free Tailwind CSS Template for Startup and SaaS By TailGrids
    </title>
    <link
      rel="shortcut icon"
      href="assets/images/favicon.png"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="./src/css/tailwind.css" />

    <!-- ==== WOW JS ==== -->
    <script src="assets/js/wow.min.js"></script>
    <script>
      new WOW().init();
    </script>
  </head>

  <body>
    <!-- ====== Navbar Section Start -->
    <div
      class="absolute top-0 left-0 z-40 flex items-center w-full bg-transparent ud-header"
    >
      <div class="container px-4 mx-auto">
        <div class="relative flex items-center justify-between -mx-4">
          <div class="max-w-full px-4 w-60">
            <a href="index.html" class="block w-full py-5 navbar-logo">
              <img
                src="assets/images/logo/logo-white.svg"
                alt="logo"
                class="w-full header-logo"
              />
            </a>
          </div>
          <div class="flex items-center justify-between w-full px-4">
            <div>
              <button
                id="navbarToggler"
                class="absolute right-4 top-1/2 block -translate-y-1/2 rounded-lg px-3 py-[6px] ring-primary focus:ring-2 lg:hidden"
              >
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-white"
                ></span>
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-white"
                ></span>
                <span
                  class="relative my-[6px] block h-[2px] w-[30px] bg-white"
                ></span>
              </button>
              <nav
                id="navbarCollapse"
                class="absolute right-4 top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg dark:bg-dark-2 lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:px-4 lg:py-0 lg:shadow-none dark:lg:bg-transparent xl:px-6"
              >
                <ul class="blcok lg:flex 2xl:ml-20">
                  <li class="relative group">
                    <a
                      href="#home"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70"
                    >
                      Home
                    </a>
                  </li>
                  <li class="relative group">
                    <a
                      href="#about"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:ml-7 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      About
                    </a>
                  </li>
                  <li class="relative group">
                    <a
                      href="#pricing"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:ml-7 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      Pricing
                    </a>
                  </li>
                  <li class="relative group">
                    <a
                      href="#team"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:ml-7 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      Team
                    </a>
                  </li>
                  <li class="relative group">
                    <a
                      href="#contact"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:ml-7 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      Contact
                    </a>
                  </li>
                  <li class="relative group">
                    <a
                      href="blog-grids.html"
                      class="flex py-2 mx-8 text-base font-medium ud-menu-scroll text-dark group-hover:text-primary dark:text-white lg:ml-7 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      Blog
                    </a>
                  </li>
                  <li class="relative submenu-item group">
                    <a
                      href="javascript:void(0)"
                      class="relative flex items-center justify-between py-2 mx-8 text-base font-medium text-dark group-hover:text-primary dark:text-white lg:ml-8 lg:mr-0 lg:inline-flex lg:py-6 lg:pl-0 lg:pr-4 lg:text-white lg:group-hover:text-white lg:group-hover:opacity-70 xl:ml-10"
                    >
                      Pages

                      <svg
                        class="ml-2 fill-current"
                        width="16"
                        height="20"
                        viewBox="0 0 16 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M7.99999 14.9C7.84999 14.9 7.72499 14.85 7.59999 14.75L1.84999 9.10005C1.62499 8.87505 1.62499 8.52505 1.84999 8.30005C2.07499 8.07505 2.42499 8.07505 2.64999 8.30005L7.99999 13.525L13.35 8.25005C13.575 8.02505 13.925 8.02505 14.15 8.25005C14.375 8.47505 14.375 8.82505 14.15 9.05005L8.39999 14.7C8.27499 14.825 8.14999 14.9 7.99999 14.9Z"
                        />
                      </svg>
                    </a>
                    <div
                      class="submenu relative left-0 top-full hidden w-[250px] rounded-xs bg-white p-4 transition-[top] duration-300 group-hover:opacity-100 dark:bg-dark-2 lg:invisible lg:absolute lg:top-[110%] lg:block lg:opacity-0 lg:shadow-lg lg:group-hover:visible lg:group-hover:top-full"
                    >
                      <a
                        href="about.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        About Page
                      </a>
                      <a
                        href="pricing.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Pricing Page
                      </a>
                      <a
                        href="contact.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Contact Page
                      </a>
                      <a
                        href="blog-grids.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Blog Grid Page
                      </a>
                      <a
                        href="blog-details.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Blog Details Page
                      </a>
                      <a
                        href="signup.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Sign Up Page
                      </a>
                      <a
                        href="signin.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        Sign In Page
                      </a>
                      <a
                        href="404.html"
                        class="block rounded-sm px-4 py-[10px] text-sm text-body-color hover:text-primary dark:text-dark-6 dark:hover:text-primary"
                      >
                        404 Page
                      </a>
                    </div>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="flex items-center justify-end pr-16 lg:pr-0">
              <label
                for="themeSwitcher"
                class="inline-flex items-center cursor-pointer"
                aria-label="themeSwitcher"
                name="themeSwitcher"
              >
                <input
                  type="checkbox"
                  name="themeSwitcher"
                  id="themeSwitcher"
                  class="sr-only"
                />
                <span class="block text-white dark:hidden">
                  <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M13.3125 1.50001C12.675 1.31251 12.0375 1.16251 11.3625 1.05001C10.875 0.975006 10.35 1.23751 10.1625 1.68751C9.93751 2.13751 10.05 2.70001 10.425 3.00001C13.0875 5.47501 14.0625 9.11251 12.975 12.525C11.775 16.3125 8.25001 18.975 4.16251 19.0875C3.63751 19.0875 3.22501 19.425 3.07501 19.9125C2.92501 20.4 3.15001 20.925 3.56251 21.1875C4.50001 21.75 5.43751 22.2 6.37501 22.5C7.46251 22.8375 8.58751 22.9875 9.71251 22.9875C11.625 22.9875 13.5 22.5 15.1875 21.5625C17.85 20.1 19.725 17.7375 20.55 14.8875C22.1625 9.26251 18.975 3.37501 13.3125 1.50001ZM18.9375 14.4C18.2625 16.8375 16.6125 18.825 14.4 20.0625C12.075 21.3375 9.41251 21.6 6.90001 20.85C6.63751 20.775 6.33751 20.6625 6.07501 20.55C10.05 19.7625 13.35 16.9125 14.5875 13.0125C15.675 9.56251 15 5.92501 12.7875 3.07501C17.5875 4.68751 20.2875 9.67501 18.9375 14.4Z"
                    />
                  </svg>
                </span>
                <span class="hidden text-white dark:block">
                  <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_2172_3070)">
                      <path
                        d="M12 6.89999C9.18752 6.89999 6.90002 9.18749 6.90002 12C6.90002 14.8125 9.18752 17.1 12 17.1C14.8125 17.1 17.1 14.8125 17.1 12C17.1 9.18749 14.8125 6.89999 12 6.89999ZM12 15.4125C10.125 15.4125 8.58752 13.875 8.58752 12C8.58752 10.125 10.125 8.58749 12 8.58749C13.875 8.58749 15.4125 10.125 15.4125 12C15.4125 13.875 13.875 15.4125 12 15.4125Z"
                      />
                      <path
                        d="M12 4.2375C12.45 4.2375 12.8625 3.8625 12.8625 3.375V1.5C12.8625 1.05 12.4875 0.637497 12 0.637497C11.55 0.637497 11.1375 1.0125 11.1375 1.5V3.4125C11.175 3.8625 11.55 4.2375 12 4.2375Z"
                      />
                      <path
                        d="M12 19.7625C11.55 19.7625 11.1375 20.1375 11.1375 20.625V22.5C11.1375 22.95 11.5125 23.3625 12 23.3625C12.45 23.3625 12.8625 22.9875 12.8625 22.5V20.5875C12.8625 20.1375 12.45 19.7625 12 19.7625Z"
                      />
                      <path
                        d="M18.1125 6.74999C18.3375 6.74999 18.5625 6.67499 18.7125 6.48749L19.9125 5.28749C20.25 4.94999 20.25 4.42499 19.9125 4.08749C19.575 3.74999 19.05 3.74999 18.7125 4.08749L17.5125 5.28749C17.175 5.62499 17.175 6.14999 17.5125 6.48749C17.6625 6.67499 17.8875 6.74999 18.1125 6.74999Z"
                      />
                      <path
                        d="M5.32501 17.5125L4.12501 18.675C3.78751 19.0125 3.78751 19.5375 4.12501 19.875C4.27501 20.025 4.50001 20.1375 4.72501 20.1375C4.95001 20.1375 5.17501 20.0625 5.32501 19.875L6.52501 18.675C6.86251 18.3375 6.86251 17.8125 6.52501 17.475C6.18751 17.175 5.62501 17.175 5.32501 17.5125Z"
                      />
                      <path
                        d="M22.5 11.175H20.5875C20.1375 11.175 19.725 11.55 19.725 12.0375C19.725 12.4875 20.1 12.9 20.5875 12.9H22.5C22.95 12.9 23.3625 12.525 23.3625 12.0375C23.3625 11.55 22.95 11.175 22.5 11.175Z"
                      />
                      <path
                        d="M4.23751 12C4.23751 11.55 3.86251 11.1375 3.37501 11.1375H1.50001C1.05001 11.1375 0.637512 11.5125 0.637512 12C0.637512 12.45 1.01251 12.8625 1.50001 12.8625H3.41251C3.86251 12.8625 4.23751 12.45 4.23751 12Z"
                      />
                      <path
                        d="M18.675 17.5125C18.3375 17.175 17.8125 17.175 17.475 17.5125C17.1375 17.85 17.1375 18.375 17.475 18.7125L18.675 19.9125C18.825 20.0625 19.05 20.175 19.275 20.175C19.5 20.175 19.725 20.1 19.875 19.9125C20.2125 19.575 20.2125 19.05 19.875 18.7125L18.675 17.5125Z"
                      />
                      <path
                        d="M5.32501 4.125C4.98751 3.7875 4.46251 3.7875 4.12501 4.125C3.78751 4.4625 3.78751 4.9875 4.12501 5.325L5.32501 6.525C5.47501 6.675 5.70001 6.7875 5.92501 6.7875C6.15001 6.7875 6.37501 6.7125 6.52501 6.525C6.86251 6.1875 6.86251 5.6625 6.52501 5.325L5.32501 4.125Z"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_2172_3070">
                        <rect width="24" height="24" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                </span>
              </label>
              <div class="hidden sm:flex">
                <a
                  href="signin.html"
                  class="loginBtn px-[22px] py-2 text-base font-medium text-white hover:opacity-70"
                >
                  Sign In
                </a>
                <a
                  href="signup.html"
                  class="px-6 py-2 text-base font-medium text-white duration-300 ease-in-out rounded-md bg-white/20 signUpBtn hover:bg-white/100 hover:text-dark"
                >
                  Sign Up
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ====== Navbar Section End -->

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1>🎓 PPDB NUIST 2025</h1>
        <p>Penerimaan Peserta Didik Baru</p>
        <p>Selamat Datang di Portal Pendaftaran Online NUIST</p>
    </div>
</div>

<!-- Info Cards -->
<div class="info-cards">
    <!-- Card 1 -->
    <div class="info-card card-1">
        <div class="info-card-icon">📋</div>
        <h3>Pendaftaran Mudah</h3>
        <p>Proses pendaftaran yang simple dan cepat hanya dalam 3 langkah</p>
    </div>

    <!-- Card 2 -->
    <div class="info-card card-2">
        <div class="info-card-icon">🔒</div>
        <h3>Data Aman</h3>
        <p>Sistem keamanan terjamin untuk melindungi data pribadi Anda</p>
    </div>

    <!-- Card 3 -->
    <div class="info-card card-3">
        <div class="info-card-icon">⚡</div>
        <h3>Hasil Cepat</h3>
        <p>Verifikasi dan hasil seleksi diumumkan dengan transparan dan cepat</p>
    </div>
</div>

<!-- Schools Section -->
<div class="schools-section">
    <div class="schools-header">
        <h2>Pilih Sekolah/Madrasah</h2>
        <p>Daftar Madrasah/Sekolah yang membuka PPDB NUIST 2025</p>
    </div>

    @if($sekolah->count() > 0)
        <div class="schools-grid">
            @foreach($sekolah as $item)
            <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="school-card">
                <!-- Header with gradient -->
                <div class="school-card-header">
                    <h3>{{ $item->nama_sekolah }}</h3>
                </div>

                <!-- Body -->
                <div class="school-card-body">
                    <span class="school-year-badge">Tahun {{ $item->tahun }}</span>

                    <!-- Status -->
                    @php
                        $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                        $statusClass = $isPembukaan ? 'status-open' : 'status-waiting';
                        $statusText = $isPembukaan ? '✓ Pendaftaran Dibuka' : '⏰ Menunggu Dibuka';
                    @endphp
                    <div class="school-status {{ $statusClass }}">
                        {{ $statusText }}
                    </div>

                    <!-- Jadwal Info -->
                    <div class="schedule-info">
                        📅 Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong>
                    </div>
                    <div class="schedule-info">
                        ⏱️ Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong>
                    </div>

                    <!-- Button -->
                    <button class="school-card-button">
                        Pelajari & Daftar →
                    </button>
                </div>
            </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Tidak ada sekolah yang membuka PPDB saat ini</h3>
            <p>Silakan kembali lagi nanti untuk melihat jadwal pendaftaran terbaru</p>
        </div>
    @endif
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <h3>Punya Pertanyaan?</h3>
        <p>Tim kami siap membantu Anda. Hubungi kami melalui:</p>
        <div class="cta-buttons">
            <a href="tel:+6281234567890" class="cta-button">📞 Hubungi Kami</a>
            <a href="mailto:ppdb@nuist.id" class="cta-button">📧 Email</a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="cta-button">💬 WhatsApp</a>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-section">
    <div class="faq-header">
        <h2>Pertanyaan Umum</h2>
    </div>

    <div class="faq-grid">
        <!-- FAQ 1 -->
        <div class="faq-item">
            <h4>❓ Syarat pendaftaran apa saja?</h4>
            <p>Siapa saja yang ingin melanjutkan sekolah dapat mendaftar dengan melengkapi dokumen yang diminta (KK dan Ijazah)</p>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-item">
            <h4>❓ Berapa biaya pendaftaran?</h4>
            <p>Pendaftaran online PPDB NUIST sepenuhnya GRATIS, tanpa ada biaya apapun</p>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-item">
            <h4>❓ Kapan hasil pengumuman?</h4>
            <p>Hasil seleksi akan diumumkan sesuai jadwal yang telah ditetapkan oleh masing-masing sekolah</p>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-item">
            <h4>❓ Bisa daftar di multiple sekolah?</h4>
            <p>Ya, Anda dapat mendaftar di beberapa sekolah sesuai keinginan Anda</p>
        </div>
    </div>
</div>

<!-- Footer Info -->
<div style="max-width: 900px; margin: 40px auto 0; padding: 0 20px;">
    <div class="info-important">
        <h3>ℹ️ Informasi Penting</h3>
        <ul>
            <li>Pastikan dokumen Anda sudah siap sebelum mendaftar</li>
            <li>Isi data dengan benar dan lengkap</li>
            <li>Simpan nomor pendaftaran Anda untuk tracking status</li>
            <li>Pastikan koneksi internet stabil saat upload dokumen</li>
            <li>Maximal ukuran file dokumen 2MB</li>
        </ul>
    </div>
</div>

<div style="height: 40px;"></div>

@endsection
