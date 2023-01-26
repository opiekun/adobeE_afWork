var config = {
    paths: {
        slick: 'Magento_Cms/js/slick/slick',
        sticky_kit: 'Magento_Cms/js/sticky-kit/sticky-kit',
        waypoints: 'Magento_Cms/js/waypoints/waypoints'
    },
    shim: {
        slick: {
            'deps':['jquery']
        },
        sticky_kit: {
            'deps':['jquery']
        },
        waypoints: {
            'deps':['jquery']
        }
    }
};
