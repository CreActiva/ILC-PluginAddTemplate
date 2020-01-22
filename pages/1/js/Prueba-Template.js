(function($){
   console.log('Prueba');
   $.stellar({
      horizontalScrolling: false,
      responsive: true
   });
   $.stellar.positionProperty.foobar = {
      setPosition: function($el, x, startX, y, startY) {
         $el.css('transform', 'translate3d(' +
         (x - startX) + 'px, ' +
         (y - startY) + 'px, ' +
         '0)');
      }
   }
   $.stellar({
      positionProperty: 'foobar'
   });
}(jQuery));
