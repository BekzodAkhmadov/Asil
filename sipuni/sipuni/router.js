import { ROLES } from "@/apps/credit/utils/constants";

export default {
    path: 'sipuni',
    name: 'sipuni-index',
    component: () => import('./index.vue'),
    meta: {
        roles: [ROLES.company, ROLES.employee, ROLES.partner],
        title: 'Sipuni',
        icon: 'solar:phone-linear',
        iconActive: 'solar:phone-bold',
        permissions: ["company.credit.sipuni-call.index", "admin.credit.sipuni-call.index"],
        translation: 'menu.sipuni'
    }
}
