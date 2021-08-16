<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Testmonial;

class Testimonials extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Testimonials',
			'description' => 'Get students testimonials'
		];
	}

	public function defineProperties(){
		return [
			'total' => [
				'title' => 'Total',
				'description' => 'Total of courses to return',
				'default' => '4',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'Only numbers allowed',
			],
			'sortOrder' => [
				'title' => 'Sort Testimonials',
				'description' => 'Sort those testimonials',
				'type' => 'dropdown',
				// 'default' => 'created_at desc'
				'default' => 'desc'
			],
		];
	}

	public function getSortOrderOptions() {
		// return [
		// 	'created_at asc' => 'Created at (ascending)',
		// 	'created_at desc' => 'Created at (descending)',
		// ];
		return [
			'asc' => 'Ordem (ascending)',
			'desc' => 'Ordem (descending)',
		];
	}

	public function onRun(){
		$this->testimonials = $this->getAllTestimonials();
	}

	// protected function getAllTestimonials() {
	// 	$query = Testmonial::all();

	// 	$query = $query->where('enabled', true);

	// 	$query = $query->sortBy($this->property('sortOrder'));

	// 	if ($this->property('total') > 0) {
	// 		$query = $query->take($this->property('total'));
	// 	}

	// 	return $query;
	// }

	protected function getAllTestimonials() {
		$query = Testmonial::where('enabled', true);
		$query = $query->orderBy('sort_order',$this->property('sortOrder'));

		$this->total=$query->count();
		if ($this->property('total') > 0) $query = $query->take($this->property('total'));

		return $query->get();
	}

	public $testimonials, $total=0;
}