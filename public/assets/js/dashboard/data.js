// Data Manager
const DashboardDataManager = {
    getData: function() {
        if (typeof window.DashboardData === 'undefined') {
            console.error("DashboardData is not defined!");
            return null;
        }
        return window.DashboardData;
    }
};