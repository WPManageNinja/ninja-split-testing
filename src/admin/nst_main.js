import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import VueQuillEditor from 'vue-quill-editor';
Vue.use(VueQuillEditor);

import 'element-ui/lib/theme-default/table.css'
import 'element-ui/lib/theme-default/popover.css'
import 'element-ui/lib/theme-default/loading.css'
import 'element-ui/lib/theme-default/message.css'
import 'element-ui/lib/theme-default/message-box.css'
import 'element-ui/lib/theme-default/tooltip.css'
import 'element-ui/lib/theme-default/pagination.css'
import 'element-ui/lib/theme-default/collapse.css'
import 'element-ui/lib/theme-default/collapse-item.css'
import 'element-ui/lib/theme-default/dialog.css'
import 'element-ui/lib/theme-default/menu.css'
import 'element-ui/lib/theme-default/menu-item.css'
import 'element-ui/lib/theme-default/button.css'
import 'element-ui/lib/theme-default/form.css'
import 'element-ui/lib/theme-default/form-item.css'
import 'element-ui/lib/theme-default/input.css'
import 'element-ui/lib/theme-default/icon.css'
import 'element-ui/lib/theme-default/select.css'
import 'element-ui/lib/theme-default/option.css'
import 'element-ui/lib/theme-default/card.css'
import 'element-ui/lib/theme-default/row.css'
import 'element-ui/lib/theme-default/col.css'
import 'element-ui/lib/theme-default/alert.css'
import 'element-ui/lib/theme-default/slider.css'
import 'element-ui/lib/theme-default/switch.css'

import {
    Table, TableColumn, Dialog, Popover, Loading, Message, MessageBox, Icon,Tooltip,
    Pagination, Collapse, CollapseItem, Menu, MenuItem, Button,  Form, Input, Select,
  	FormItem, Option, Card,Row,Col, Alert, Slider, Switch
} from 'element-ui';

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
// configure language
locale.use(lang);

Vue.use(Table);
Vue.use(Pagination);
Vue.use(TableColumn);
Vue.use(Popover);
Vue.use(Loading);
Vue.use(Icon);
Vue.use(Tooltip);
Vue.use(Collapse);
Vue.use(CollapseItem);
Vue.use(Dialog);
Vue.use(Menu);
Vue.use(MenuItem);
Vue.use(Button);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Input);
Vue.use(Select);
Vue.use(Option);
Vue.use(Card);
Vue.use(Row);
Vue.use(Col);
Vue.use(Alert);
Vue.use(Slider);
Vue.use(Switch);

Vue.prototype.$message = Message;
Vue.prototype.$msgbox = MessageBox;
Vue.prototype.$alert = MessageBox.alert;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$prompt = MessageBox.prompt;

import {routes} from './nst_routes';
import NstApplication from './NstApp.vue';

Vue.mixin({
	methods: {
		componentEmit(name, value) {
			value ? this.$emit(name, value) : this.$emit(name);
		}
	}
});

const router = new VueRouter({
    routes
});

window.nstTableBus = new Vue();

NstApplication.router = router;

window.nstApp = new Vue(NstApplication).$mount('#nst-app');
