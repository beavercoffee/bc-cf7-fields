if('undefined' === typeof(bc_cf7_floating_labels)){
    var bc_cf7_floating_labels = {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        init: function(){
            jQuery('.wpcf7-form').on('wpcf7reset', bc_cf7_floating_labels.wpcf7reset);
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        wpcf7reset: function(){
            bc_floating_labels.both();
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    };
}
