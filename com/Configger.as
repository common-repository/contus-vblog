package com{

	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.display.Sprite;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.external.*;
	public class Configger extends EventDispatcher {


		public var config:Object = {
		server:undefined,
		filename:'webflv' + Math.round(new Date().getTime()),
		mode:'recorder',
		maxduration:200,
		bandwidth:0,
		quality:0,
		width:320,
		height:240,
		keyframe:15,
		fps:15,
		loopback:false,
		micrate:8,
		silencelevel:10
		};


		public var obj:Object;
		public static  var reference:Sprite;
		protected var loader:URLLoader;


		public function Configger(ref:Sprite):void {
			reference = ref;
			loadCookies();
		}


		protected function loadCookies():void {
			var xml:String = reference.root.loaderInfo.parameters['config'];
			if (xml) {
				loadXML(Strings.decode(xml));
			} else {
				loadFlashvars();
			}
		}


		protected function loadXML(url:String):void {
			loader = new URLLoader();
			loader.addEventListener(Event.COMPLETE,xmlHandler);
			try {
				loader.load(new URLRequest(url));
			} catch (err:Error) {
				loadFlashvars();
			}
		}


		protected function xmlHandler(evt:Event):void {
			var dat:XML = XML(evt.currentTarget.data);
			obj = new Object();
			for each (var prp:XML in dat.children()) {
				obj[prp.name()] = prp.text();
			}
			compareWrite(obj);
			loadFlashvars();
		}


		protected function loadFlashvars():void {
			compareWrite(reference.root.loaderInfo.parameters);
			dispatchEvent(new Event(Event.COMPLETE));
		}


		protected function compareWrite(obj:Object):void {
			for (var cfv:String in obj) {
				config[cfv.toLowerCase()] = Strings.serialize(obj[cfv.toLowerCase()]);
			}
		}



	}


}