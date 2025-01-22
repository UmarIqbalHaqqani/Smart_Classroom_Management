<?php

namespace App\View\Components;

use App\SmTestimonial;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Testimonial extends Component
{
    public $count;
    public $sorting;
    
    public function __construct($count = 3, $sorting ='asc')
    {
        $this->count = $count;
        $this->sorting = $sorting;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $testimonials = SmTestimonial::query();
        $testimonials ->where('school_id', app('school')->id);
        if($this->sorting =='asc'){
            $testimonials->orderBy('id','asc');
        }
        elseif($this->sorting =='desc'){
            $testimonials->orderBy('id','desc');
        }
        else{
            $testimonials->inRandomOrder();
        }

        $testimonials = $testimonials->take($this->count)->get(['name', 'designation', 'institution_name', 'image', 'description', 'star_rating', 'school_id']);
        return view('components.'.activeTheme().'.testimonial', compact('testimonials'));
    }
}
