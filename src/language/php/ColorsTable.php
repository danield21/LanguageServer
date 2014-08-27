<?php
	require_once 'Color.php';
	class ColorsTable {
		public function get_xml_colors($file = "../styles/Colors.xml") {
			$array = [];
			$xml = simplexml_load_file($file);
			foreach($xml->color as $color) {
				foreach($color->version as $version) {
					$array[(string)$color['id']][(string)$version['type']] = new Color(
							(int)$version->red['value'],
							(int)$version->green['value'],
							(int)$version->blue['value']
						);
				}
			}
			return $array;
		}
	}
?>