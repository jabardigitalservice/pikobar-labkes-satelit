<template>
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse" v-if="user">
      <ul class="nav metismenu" id="side-menu">
        <li class="nav-header">
          <div class="dropdown profile-element">
            <!-- <img alt="image" class="rounded-circle" src="~/assets/img/profile_small.jpg" /> -->
            <img alt="image" class="img-fluid" src="~/assets/img/logo-pikobar-jawabarat-text-white.png" />
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <span class="block m-t-xs font-bold">{{user.name}}</span>
              <span class="text-muted text-xs block">
                {{user.email}}
                <b class="caret"></b>
              </span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
              <li>
                <router-link :to="{name: 'settings.profile'}" class="dropdown-item">Profile</router-link>
              </li>
              <li class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="#" @click.prevent="logout()">Logout</a>
              </li>
            </ul>
          </div>
          <div class="logo-element">IN+</div>
        </li>
        <li>
          <router-link to="/" tag="a">
            <i class="fa fa-home fa-fw"></i>
            <span class="nav-label">Dashboard</span>
          </router-link>
        </li>
        <li>
          <a href="#">
            <i class="uil-user-square fa-fw"></i>
            <span class="nav-label">Registrasi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/registrasi/mandiri" tag="a">
                <span class="nav-label">Registrasi Mandiri (L)</span>
              </router-link>
            </li>
            <li>
              <router-link to="/" tag="a">
                <span class="nav-label">Registrasi Rujukan (R)</span>
              </router-link>
            </li>
          </ul>
        </li>
        <li>
          <router-link to="/sample" tag="a">
            <i class="uil-flask-potion fa-fw"></i>
            <span class="nav-label">Pengambilan Sample</span>
          </router-link>
        </li>
        <li>
          <a href="#">
            <i class="uil-flask fa-fw"></i>
            <span class="nav-label">Ekstraksi</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/ekstraksi" tag="a">
                <span class="nav-label">Ekstraksi</span>
              </router-link>
            </li>
            <li>
              <router-link to="/ekstraksi/dikembalikan" tag="a">
                <span class="nav-label">Sampel yang dikembalikan</span>
              </router-link>
            </li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="uil-atom fa-fw"></i>
            <span class="nav-label">Pemeriksaan RT-PCR</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <router-link to="/" tag="a">
                <span class="nav-label">Pemeriksaan Sampel</span>
              </router-link>
            </li>
            <li>
              <router-link to="/" tag="a">
                <span class="nav-label">Sampel yang dikembalikan</span>
              </router-link>
            </li>
          </ul>
        </li>
        <li>
          <router-link to="/" tag="a">
            <i class="uil-eye fa-fw"></i>
            <span class="nav-label">Verifikasi</span>
          </router-link>
        </li>
        <li>
          <router-link to="/" tag="a">
            <i class="uil-check fa-fw"></i>
            <span class="nav-label">Validasi</span>
          </router-link>
        </li>
        <li>
          <router-link to="/" tag="a">
            <i class="uil-search-alt fa-fw"></i>
            <span class="nav-label">Pelacakan Sampel</span>
          </router-link>
        </li>
        <li>
          <a href="#">
            <i class="uil-database-alt fa-fw"></i>
            <span class="nav-label">Data Master</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level collapse">
            <li>
              <nuxt-link to="/pengguna">Pengguna</nuxt-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from "vuex";
import LocaleDropdown from "./LocaleDropdown";
// require('metismenu');
// import MetisMenu from 'metismenujs'

export default {
  components: {
    LocaleDropdown,
  },

  data: () => ({
    appName: process.env.appName,
  }),

  computed: mapGetters({
    user: "auth/user"
  }),

  methods: {
    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");

      this.$toast.success("Logout sukses", {
        icon: 'check',
        iconPack: 'fontawesome',
        duration: 5000
      })

      // Redirect to login.
      this.$router.push({ name: "login" });
    }
  },
  mounted() {
    this.$nextTick(function() {
      // Fast fix bor position issue with Propper.js
      // Will be fixed in Bootstrap 4.1 - https://github.com/twbs/bootstrap/pull/24092
      Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;

      // Add body-small class if window less than 768px
      if (window.innerWidth < 769) {
        $("body").addClass("body-small");
      } else {
        $("body").removeClass("body-small");
      }

      // MetisMenu
      if (window && window.$ && window.$('#side-menu').metisMenu) {
        var sideMenu = window.$('#side-menu').metisMenu();
      }

      // Move right sidebar top after scroll
      $(window).scroll(function() {
        if ($(window).scrollTop() > 0 && !$("body").hasClass("fixed-nav")) {
          $("#right-sidebar").addClass("sidebar-top");
        } else {
          $("#right-sidebar").removeClass("sidebar-top");
        }
      });

      // Add slimscroll to element
      // $('.full-height-scroll').slimscroll({
      //     height: '100%'
      // })
    });

    // Minimalize menu when screen is less than 768px
    $(window).bind("resize", function() {
      if (window.innerWidth < 769) {
        $("body").addClass("body-small");
      } else {
        $("body").removeClass("body-small");
      }
    });

    // Fixed Sidebar
    $(window).bind("load", function() {
      if ($("body").hasClass("fixed-sidebar")) {
        // $('.sidebar-collapse').slimScroll({
        //     height: '100%',
        //     railOpacity: 0.9
        // });
      }
    });

    // check if browser support HTML5 local storage
    function localStorageSupport() {
      return "localStorage" in window && window["localStorage"] !== null;
    }

    // Local Storage functions
    // Set proper body class and plugins based on user configuration
    $(document).ready(function() {
      if (localStorageSupport()) {
        var collapse = localStorage.getItem("collapse_menu");
        var fixedsidebar = localStorage.getItem("fixedsidebar");
        var fixednavbar = localStorage.getItem("fixednavbar");
        var boxedlayout = localStorage.getItem("boxedlayout");
        var fixedfooter = localStorage.getItem("fixedfooter");

        var body = $("body");

        if (fixedsidebar == "on") {
          body.addClass("fixed-sidebar");
          // $('.sidebar-collapse').slimScroll({
          //     height: '100%',
          //     railOpacity: 0.9
          // });
        }

        if (collapse == "on") {
          if (body.hasClass("fixed-sidebar")) {
            if (!body.hasClass("body-small")) {
              body.addClass("mini-navbar");
            }
          } else {
            if (!body.hasClass("body-small")) {
              body.addClass("mini-navbar");
            }
          }
        }

        if (fixednavbar == "on") {
          $(".navbar-static-top")
            .removeClass("navbar-static-top")
            .addClass("navbar-fixed-top");
          body.addClass("fixed-nav");
        }

        if (boxedlayout == "on") {
          body.addClass("boxed-layout");
        }

        if (fixedfooter == "on") {
          $(".footer").addClass("fixed");
        }
      }
    });

    // For demo purpose - animation css script
    function animationHover(element, animation) {
      element = $(element);
      element.hover(
        function() {
          element.addClass("animated " + animation);
        },
        function() {
          //wait for animation to finish before removing classes
          window.setTimeout(function() {
            element.removeClass("animated " + animation);
          }, 2000);
        }
      );
    }

    function SmoothlyMenu() {
      if (
        !$("body").hasClass("mini-navbar") ||
        $("body").hasClass("body-small")
      ) {
        // Hide menu in order to smoothly turn on when maximize menu
        $("#side-menu").hide();
        // For smoothly turn on menu
        setTimeout(function() {
          $("#side-menu").fadeIn(400);
        }, 200);
      } else if ($("body").hasClass("fixed-sidebar")) {
        $("#side-menu").hide();
        setTimeout(function() {
          $("#side-menu").fadeIn(400);
        }, 100);
      } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $("#side-menu").removeAttr("style");
      }
    }

    // Dragable panels
    function WinMove() {
      var element = "[class*=col]";
      var handle = ".ibox-title";
      var connect = "[class*=col]";
      $(element)
        .sortable({
          handle: handle,
          connectWith: connect,
          tolerance: "pointer",
          forcePlaceholderSize: true,
          opacity: 0.8
        })
        .disableSelection();
    }
    // END MOUNTED
  }
};
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -0.375rem 0;
}
</style>
