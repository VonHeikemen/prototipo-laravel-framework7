var dd = console.log.bind(console);

// Initialize your app
var f7 = new Framework7({
    material: true,
    modalTitle: 'CAOL - Agence Interativa',
    smartSelectSearchbar: true,
    smartSelectOpenIn: 'picker'
});

// Export selectors engine
var $$ = Dom7;

f7.ready = function(callback) {
  document.readyState === "interactive" || 
  document.readyState === "complete" 
    ? callback() 
    : document.addEventListener("DOMContentLoaded", callback);
};
