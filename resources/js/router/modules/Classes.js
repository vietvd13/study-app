import Layout from '@/layout';

const Classes = {
	path: '/classes',
	name: 'Classes',
	meta: {
		title: 'ROUTER.CLASSES',
		icon: 'fad fa-users-class'
	},
	component: Layout,
	redirect: { name: 'ClassesIndex' },
	children: [
		{
			path: 'index',
			name: 'ClassesIndex',
			meta: {
				title: 'ROUTER.CLASSES'
			},
			component: () => import(/* webpackChunkName: "Classes" */ '@/pages/Classes/index')
		}
	]
};

export default Classes;
