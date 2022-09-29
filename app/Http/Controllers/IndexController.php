<?php

namespace App\Http\Controllers;


use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use App\Repositories\SlidersRepository;
use App\Repositories\ArticlesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Config;


class IndexController extends SiteController
{

    public function __construct(SlidersRepository $s_rep, PortfoliosRepository $p_rep, ArticlesRepository $a_rep)
    {

        parent::__construct(new MenusRepository(new \App\Models\Menu));

        $this->s_rep = $s_rep;
        $this->p_rep = $p_rep;
        $this->a_rep = $a_rep;

        $this->bar = 'right';
        $this->template = config('settings.theme') . '.index';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $portfolios = $this->getPortfolio();
        $content = view(config('settings.theme') . '.content')->with('portfolios', $portfolios)->render();
        $this->vars = Arr::add($this->vars, 'content', $content);

        $sliderItems = $this->getSliders();

        $sliders = view(config('settings.theme') . '.slider')->with('sliders', $sliderItems)->render();
        $this->vars = Arr::add($this->vars, 'sliders', $sliders);

        $this->keywords = 'Home Page';
        $this->meta_desc = 'Home Page';
        $this->title = 'Home Page';

        $articles = $this->getArticles();

        $this->contentRightBar = view(config('settings.theme') . '.indexBar')->with('articles', $articles)->render();

        return $this->renderOutput();
    }

    protected function getArticles()
    {

        $articles = $this->a_rep->get(['title', 'created_at', 'img', 'alias'], Config::get('settings.home_articles_count'));

        return $articles;
    }

    protected function getPortfolio()
    {
        $portfolio = $this->p_rep->get('*', Config::get('settings.home_port_count'));

        return $portfolio;
    }

    public function getSliders()
    {

        $sliders  = $this->s_rep->get();

        if ($sliders->isEmpty()) {
            return FALSE;
        }

        $sliders->transform(function ($item, $key) {

            $item->img = Config::get('settings.slider_path') . '/' . $item->img;
            return $item;
        });


        return $sliders;
    }
}
