Vue.component("nav-item", {
    template: `
        <li class="has-treeview" :class="{'menu-open':menu.active}">
            <a :href="menu.link" class="nav-link" :class="{active:isActive}">
                <i class="nav-icon" :class="menu.icon"></i>
                <p>
                    {{menu.label}}
                    <i v-if="menu.submenu" class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul v-if="menu.submenu" class="nav nav-treeview">
                <li class="nav-item" v-for="(sub,index) in menu.submenu" is="nav-item" :menu="sub" :key="index"></li>
            </ul>
        </li>
        `,
    props: {
        menu: Object,
        isRoot: Boolean
    }, mounted() {

    }, computed: {
        isActive() {
            if (this.isRoot && this.menu.active) {
                return true;
            }
            if (this.menu.submenu) {
                return false;
            }
            return this.menu.active;
        }

    }
});

var v = new Vue({
    el: "#nav-sidebar"
});