<?php

namespace Helium\Core;

use Helium\Helium;

class View
{
    protected $data;
    protected $path;

    /**
     * View constructor.
     * @param $data
     * @param $path
     */
    public function __construct( &$data, $path )
    {
        $viewPath = 'src' . DS . 'views' . DS . $path;
        if( file_exists($viewPath) ) {
            $this->path = $viewPath;
        }
            
        $this->data = $data;
    }

    public function render()
    {
        $data = &$this -> data;
        if ( !empty( $this->path ) ) {
            ob_start();
            require $this->path;
            $content = ob_get_clean();
        } else {
            $content = 'Template does not exist';
        }

        return $content;
    }

    public function url(string$uri, array $params = []): string
    {
        $domain = Helium::getConfig('domain');
        $redirect_base = Helium::getConfig('redirect_base');

        $param_string = '';
        if ( count($params) > 0 ) {
            $param_parts = [];
            foreach ( $params as $key => $value ) {
                $param_parts[] = urlencode(urldecode($key)) . '=' . urlencode(urldecode($value));
            }
            $param_string = '?' . implode('&', $param_parts);
        }

        return $domain . DS . $redirect_base . trim( $uri, DS ) . $param_string;
    }

    public function assets(string $uri): string
    {
        $domain = Helium::getConfig('domain');
        $asset_dir = Helium::getConfig('asset_dir');

        return $domain . DS . $asset_dir . DS . trim( $uri, DS );
    }

    public function title(): string
    {
        return Helium::getConfig('app_title');
    }

    public function menu(string $controller = ''): array
    {
        $helium = new Helium();
        $visible = $helium->getVisibleRoutes();
        $menuItems = [];

        foreach ($visible as $item) {
            if ($item === 'default/index') {
                continue;
            }
            $parts = explode('/', $item);
            $item = str_replace(['/index', 'default/'], '', $item);

            if (!empty($controller)) {
                if ($controller === $parts[0]) {
                    $menuItems[] = $item;
                }
            } else {
                $menuItems[] = $item;
            }
        }

        return $menuItems;
    }
}