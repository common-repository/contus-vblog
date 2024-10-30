$(document).ready(function(){
	works();
});
function works(){
	var carusel = $('.carousel');
	carusel.each(function(i, _carusel){
		_carusel = $(_carusel);
		var _caruselTab = _carusel.parents('.tab')
		var _btNext = $('a.right', _caruselTab);
		var _btPrev = $('a.left', _caruselTab);
		var _informBlock = $('> li', _carusel);
		var _current = 0;
		var _step = _carusel.parent().width();
		var _next;
		var _duration = 300;

		// set paremetr
		if (_informBlock.filter('.active').length) {
			_informBlock.not('.active').css('left',_step);
			_current = _informBlock.index(_informBlock.filter('.active'));
		}
		else {
			_informBlock.not(':eq(0)').css('left',_step);
		}

		// generate inform ul
		var _inform = '<ul class="carousel-info">';
		_informBlock.each(function(i, block){
			var _inf = $(block).find('div.info').html();         
			_inform += '<li>'+_inf+'</li>';
		});
		_inform += '</ul>';
		_carusel.after(_inform);

		var _text = $('ul.carousel-info', _caruselTab);
		var _informText = $('> li', _text);
		var _heightText = _informText.outerHeight();

		if (_informBlock.filter('.active').length) _informText.not(':eq('+_current+')').css('top',_heightText);
		else _informText.not(':eq(0)').css('top',_heightText);

		// set pager
		var _pager = $('ul.gallery-btns', _caruselTab);
		var _li = '';
		_informBlock.each(function(i, block){
			_li += '<li><a href="#">button</a></li>';
		});
		_pager.html(_li);
		_pager.find('li:eq('+_current+')').addClass('active');
		_pager.find('a').click(function(){
			var _index = _pager.find('li a').index($(this));
			_next = _index;

			if (_next > _current) animateNext();
			else animatePrev();

			_current = _next;
			setActive();
			return false;
		});

		// next/prev
		_btNext.click(function(){
			if (!_informBlock.is(':animated')) {
				_next = _current+1
				if (_next <= _informBlock.length-1)
                    {

				animateNext();
				_current = _next;
				setActive();
					}
			}
			return false;
		});
		_btPrev.click(function(){
			if (!_informBlock.is(':animated')) {
				_next = _current-1;
				if (_next >= 0) { //_next = _informBlock.length-1;

				animatePrev();
				_current = _next;
				setActive();
				}
			}
			return false;
		});

		function animateNext() {
			_informBlock.eq(_next).css('left',_step).animate({left:0},{duration:_duration});
			_informBlock.eq(_current).animate({left:-_step},{duration:_duration});

			_informText.eq(_next).css('top',-_heightText).animate({top:0},{duration:_duration});
			_informText.eq(_current).animate({top:_heightText},{duration:_duration});
		}

		function animatePrev() {
			_informBlock.eq(_next).css('left',-_step).animate({left:0},{duration:_duration});
			_informBlock.eq(_current).animate({left:_step},{duration:_duration});

			_informText.eq(_next).css('top',_heightText).animate({top:0},{duration:_duration});
			_informText.eq(_current).animate({top:-_heightText},{duration:_duration});
		}

		function setActive(){
			_pager.find('li').removeClass('active');
			_pager.find('li:eq('+_current+')').addClass('active');
		}
	});
}