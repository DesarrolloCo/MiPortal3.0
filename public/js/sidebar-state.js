/**
 * Sidebar State Management with localStorage
 * Mantiene el estado del sidebar (expandido/contraído) en localStorage
 * Se ejecuta después de todos los scripts para evitar conflictos
 */

// Namespace global para evitar conflictos
window.SidebarStateManager = {
    storageKey: 'mi-portal-sidebar-state',

    // Función para cargar el estado del sidebar desde localStorage
    loadState: function() {
        var savedState = localStorage.getItem(this.storageKey);
        console.log('🔍 Estado guardado del sidebar:', savedState);

        if (savedState === 'collapsed') {
            console.log('⬅️ Aplicando estado colapsado');
            this.applySidebarState(true);
        } else if (savedState === 'expanded') {
            console.log('➡️ Aplicando estado expandido');
            this.applySidebarState(false);
        }
    },

    // Función para aplicar el estado del sidebar
    applySidebarState: function(isCollapsed) {
        if (isCollapsed) {
            $("body").addClass("mini-sidebar");
            $(".navbar-brand span").hide();
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $(".sidebartoggler i").removeClass("ti-menu");
        } else {
            $("body").removeClass("mini-sidebar");
            $(".navbar-brand span").show();
            $(".scroll-sidebar, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
            $(".sidebartoggler i").addClass("ti-menu");
        }
    },

    // Función para guardar el estado del sidebar en localStorage
    saveState: function(state) {
        localStorage.setItem(this.storageKey, state);
        console.log('💾 Estado del sidebar guardado:', state);
    },

    // Función para inicializar el sistema
    init: function() {
        var self = this;

        // Cargar estado inicial
        this.loadState();

        // Remover event handlers existentes y agregar el nuestro
        $(".sidebartoggler").off('click.sidebarState').on('click.sidebarState', function(e) {
            // Pequeño delay para ejecutar después del handler original
            setTimeout(function() {
                var isCurrentlyCollapsed = $("body").hasClass("mini-sidebar");
                var newState = isCurrentlyCollapsed ? 'collapsed' : 'expanded';
                self.saveState(newState);
                console.log('🔄 Toggle sidebar - Nuevo estado:', newState);
            }, 10);
        });

        // Monitorear cambios en la clase mini-sidebar para detectar cambios externos
        var lastState = $("body").hasClass("mini-sidebar");
        setInterval(function() {
            var currentState = $("body").hasClass("mini-sidebar");
            if (currentState !== lastState) {
                var newState = currentState ? 'collapsed' : 'expanded';
                self.saveState(newState);
                lastState = currentState;
                console.log('🔄 Estado detectado externamente:', newState);
            }
        }, 500);

        console.log('✅ SidebarStateManager inicializado correctamente');
    }
};

// Esperar a que todo esté cargado y ejecutar después de un pequeño delay
$(document).ready(function() {
    // Ejecutar después de un delay para asegurar que todos los scripts se han cargado
    setTimeout(function() {
        window.SidebarStateManager.init();
    }, 250);
});