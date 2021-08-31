<?php

namespace Helium\Core;

class Controller
{
    protected $model;
    protected $action;
    protected $params;

    protected $head;
    protected $data;

    /**
     * Controller constructor.
     * @param string $model
     * @param string $action
     * @param array $params
     */
    public function __construct(string $model, string $action, array $params = [])
    {
        $this->model = $model;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Render controller, Web view is rendered if no path specified.
     *
     * @param array $data
     * @param array $layoutData
     * @param array $moreStylesheets
     * @param array $moreScripts
     * @return string
     */
    function render( array $data = [], array $layoutData = [], array $moreStylesheets = [], array $moreScripts = []): string
    {
        $metaData = [];
        if ( array_key_exists( 'meta', $layoutData) && !empty( $layoutData['meta'] ) ) {
            $metaData = $layoutData['meta'];
        }

        //Layout Paths
        $layoutPath = 'layout.php';
        $layoutNavPath = 'nav.php';
        $layoutFooterPath = 'footer.php';
        $layoutMetaPath = 'meta.php';

        $bodyLayoutPath = $this->model. DS .$this->action.'.php';

        //Create Meta / Nav / Footer / Body Views Instances
        $bodyView = new View( $data, $bodyLayoutPath );
        $navView = new View( [], $layoutNavPath );
        $footerView = new View( [], $layoutFooterPath );
        $metaView = new View( $metaData, $layoutMetaPath );

        //Creates an array that contains layouts required data 
        $renderData = array(
            'meta' => $metaView->render(), 
            'nav' => $navView->render(), 
            'content' => $bodyView->render(), 
            'footer' => $footerView->render(), 
            'more_stylesheets' => $moreStylesheets, 
            'more_scripts' => $moreScripts, 
        );

        //Render Full Layout
        $layoutView = new View($renderData, $layoutPath);

        return $layoutView->render();
    }

    public function error404(): string
    {
        return $this->render();
    }

    public function redirect( $uri, $params = array() )
    {
        $view = new View([], null);
        header( 'Location: ' . $view->url( $uri, $params ) );
    }
}