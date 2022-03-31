const getters = {
	language: state => state.app.language,
	permissionRoutes: state => state.permissions.routes,
	addRoutes: state => state.permissions.addRoutes
};

export default getters;
