<?php
class Color {
	public $red;
	public $green;
	public $blue;
	private static $max = 255;
	
	public function __construct($red = 0, $green = 0, $blue = 0) {
		$this->red = $red;
		$this->green = $green;
		$this->blue = $blue;
	}
	
	public function to_hex() {
		$hex = '';
		$hex .= str_pad(dechex(self::validate_number($this->red)), 2, '0', STR_PAD_LEFT);
		$hex .= str_pad(dechex(self::validate_number($this->green)), 2, '0', STR_PAD_LEFT);
		$hex .= str_pad(dechex(self::validate_number($this->blue)), 2, '0', STR_PAD_LEFT);
		return strtoupper($hex);
	}
	
	public function to_array() {
		return array(
			'red' => self::validate_number($this->red),
			'green' => self::validate_number($this->green),
			'blue' => self::validate_number($this->blue),
		);
	}
	
	public function to_greyscale() {
		$gray = (self::validate_number($this->red) + self::validate_number($this->green) + self::validate_number($this->blue)) / 3;
		return new Color($gray, $gray, $gray);
	}
	
	public function lighter($shade) {
		return $this->add(new Color($shade));
	}
	
	public function darker($shade) {
		return $this->subtract(new Color($shade));
	}
	
	public function add(Color $color) {
		$red = self::validate_number(self::validate_number($this->red) + $color->red);
		$green = self::validate_number(self::validate_number($this->green) + $color->red);
		$blue = self::validate_number(self::validate_number($this->blue) + $color->red);
		return new Color($red, $green, $blue);
	}
	
	public function subtract(Color $color) {
		$red = self::validate_number(self::validate_number($this->red) - $color->red);
		$green = self::validate_number(self::validate_number($this->green) - $color->red);
		$blue = self::validate_number(self::validate_number($this->blue) - $color->red);
		return new Color($red, $green, $blue);
	}
	
	public function invert() {
		return new Color((255 - self::validate_number($this->red)), (255 - self::validate_number($this->green)), (255 - self::validate_number($this->blue)));
	}
	
	public function __toString() {
		return "#" . $this->to_hex();
	}
	
	public static function random_color() {
		return new Color(rand(0,255), rand(0,255), rand(0,255));
	}
	
	private static function color_to_hex($color) {
		$hex = str_pad(dechex(self::validate_number($this->red)), 2, '0', STR_PAD_LEFT);
	}
	
	private static function validate_number($color) {
		if(!is_numeric($color)) {
			return 0;
		}
		$number = (int)$color;
		if($number > 255) {
			return 255;
		}
		if($number < 0) {
			return 0;
		}
		return $number;
	}
}
?>
