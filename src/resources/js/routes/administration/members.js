const MembersIndex = () => import('../../pages/administration/members/Index.vue');

export default {
    name: 'administration.members.index',
    path: 'members',
    component: MembersIndex,
    meta: {
        breadcrumb: 'members',
        title: 'Members',
    },
};
