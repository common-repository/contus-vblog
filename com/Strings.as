package com{

	public class Strings {


		public static function decode(str:String):String {
			if (str.indexOf('asfunction') == -1) {
				return unescape(str);
			} else {
				return '';
			}
		}


		public static function serialize(val:String):Object {
			if (val == null) {
				return null;
			} else if (val == 'true') {
				return true;
			} else if (val == 'false') {
				return false;
			} else if (isNaN(Number(val)) || val.length > 5) {
				return Strings.decode(val);
			} else {
				return Number(val);
			}
		}


	}


}