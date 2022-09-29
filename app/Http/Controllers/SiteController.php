<?php

namespace App\Http\Controllers;

use App\Repositories\MenusRepository;
use Illuminate\Support\Arr;
use Menu;

class SiteController extends Controller
{

  protected $p_rep;
  protected $s_rep;
  protected $a_rep;
  protected $m_rep;

  protected $keywords;
  protected $meta_desc;
  protected $title;

  protected $template;

  protected $vars = array();

  protected $contentRightBar = FALSE;
  protected $contentLeftBar = FALSE;


  protected $bar = 'no';

  public function __construct(MenusRepository $m_rep)
  {

    $this->m_rep = $m_rep;
  }
  public function getMenu()
  {
    $menu = $this->m_rep->get();

    $mBuilder = Menu::make('MyNav', function ($m) use ($menu) {
      foreach ($menu as $item) {
        if ($item->parent == 0) {
          $m->add($item->title, $item->path)->id($item->id);
        } else {

          if ($m->find($item->parent)) {
            $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
          }
        }
      }
    });

    return $mBuilder;
  }
  protected function renderOutput()
  {

    $menu = $this->getMenu();

    $navigation = view(config('settings.theme') . '.navigation')->with('menu', $menu)->render();

    $this->vars = Arr::add($this->vars, 'navigation', $navigation);

    if ($this->contentRightBar) {
      $rightBar = view(config('settings.theme') . '.rightBar')->with('content_rightBar', $this->contentRightBar)->render();
      $this->vars = Arr::add($this->vars, 'rightBar', $rightBar);
    }

    if ($this->contentLeftBar) {
      $leftBar = view(config('settings.theme') . '.leftBar')->with('content_leftBar', $this->contentLeftBar)->render();
      $this->vars = Arr::add($this->vars, 'leftBar', $leftBar);
    }

    $this->vars = Arr::add($this->vars, 'bar', $this->bar);

    $this->vars = Arr::add($this->vars, 'keywords', $this->keywords);
    $this->vars = Arr::add($this->vars, 'meta_desc', $this->meta_desc);
    $this->vars = Arr::add($this->vars, 'title', $this->title);

    $footer = view(config('settings.theme') . '.footer')->render();
    $this->vars = Arr::add($this->vars, 'footer', $footer);


    return view($this->template)->with($this->vars); //

  }
}
