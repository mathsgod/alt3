<template>
  <li class="has-treeview" :class="getClass">
    <a :href="menu.link" class="nav-link" :class="{ active: isActive }">
      <i class="nav-icon" :class="menu.icon"></i>
      <p>
        {{ menu.label }}
        <i v-if="menu.submenu" class="right fas fa-angle-left"></i>
        <span
          class="badge right"
          :class="getBadgeClass()"
          v-if="hasBadge"
          v-text="menu.badge.content"
        ></span>
      </p>
    </a>
    <ul v-if="menus.submenu" class="nav nav-treeview">
      <li
        class="nav-item"
        v-for="(sub, index) in menus.submenu"
        is="nav-item"
        :menu="sub"
        :key="index"
      ></li>
    </ul>
  </li>
</template>
<script>
export default {
  props: {
    menu: Object,
    isRoot: Boolean,
  },
  data() {
    return {
      q: "",
    };
  },
  mounted() {},
  computed: {
    getClass() {
      var c = {
        "menu-open": this.menu.active,
      };

      if (this.q != "") {
        if (
          this.menu.keyword.toLowerCase().indexOf(this.q.toLowerCase()) < 0 &&
          this.menus.submenu.length == 0
        ) {
          c["d-none"] = true;
        }
      }
      return c;
    },
    isActive() {
      if (this.isRoot && this.menu.active) {
        return true;
      }
      if (this.menu.submenu) {
        return false;
      }
      return this.menu.active;
    },
    menus() {
      if (this.q == "") {
        return this.menu;
      }
      var m=this.menu;
      m.submenu = this.filterMenu(
        this.q.toLowerCase(),
        m.submenu
      );
      return m;
    },
    hasBadge() {
      if (this.menu.badge) {
        return true;
      }
      return false;
    },
  },
  methods: {
    getBadgeClass() {
      if (this.menu.badge) {
        return "badge-" + this.menu.badge.class;
      }
    },
    filterMenu(text, menu) {
      var m = [];
      for (var i in menu) {
        if (menu[i].keyword.toLowerCase().indexOf(text.toLowerCase()) >= 0) {
          m.push(menu[i]);
          continue;
        }

        menu[i].submenu = this.filterMenu(text, menu[i].submenu);
        if (menu[i].submenu.length > 0) {
          m.push(menu[i]);
        }
      }
      return m;
    },
  },
};
</script>