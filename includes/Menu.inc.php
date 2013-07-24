<?php
class Menu {

	private $pages = array();
	private $pages_dir;
	private $html = '';

	function __construct($directory)
		{
		global $nails;
		
		$this->pages_dir = dir($directory);
		
		while($filename = $this->pages_dir->read())
			{
			if ($filename[0] != '.' && substr($filename, -1) != '~' && $filename != NAILS_NOTFOUND_FILE && is_file($directory.'/'.$filename))
				{
				$pos = strpos($filename, '.');
				$name = substr(substr($filename, $pos + 1), 0, -4);
			
				if (substr($name, 0, 2) == 'X.')
					{
					$published = false;

					if (User::isLogged())
						{
						$name = substr($name, 2);
						$in_menu = true;
						}
					else
						{
						$in_menu = false;
						}
					}
				else
					{
					$published = true;
					$in_menu = true;
					}

				$name_lc = strtolower($name);
			
				if ($in_menu)
					{
					$alias_path = $this->pages_dir->path == NAILS_DIR_PAGES ? '' : substr($this->pages_dir->path, 6).'/';

					if (substr($filename, 0, 2) != 'H.') // Not hidden
						{
						// Not a hidden file
						$this->pages[substr($filename, 0, $pos)] = array(
															'menutext' => ($published ? '' : NAILS_NAV_UNPUBPREFIX).$name,
															'alias' => $alias_path.$name_lc,
															'submenu' => (is_dir($directory.'/'.$name_lc) ? new Menu($directory.'/'.$name_lc) : null)
															);
						}

					if ($alias_path.$name_lc == $nails['request'] || (!$alias_path && $name_lc == NAILS_ALIAS_HOME && empty($nails['request'])))
						{
						$nails['page_alias'] = $alias_path.$name_lc;
						$nails['page_title'] = ($published ? '' : 'UNPUBLISHED | ').str_replace('-', ' ', $name);
						$nails['page_filename'] = $alias_path.$filename;
						}
					
					}
				}
			}
		
		// Order pages for menu
		ksort($this->pages);
		}
	
	function __get($name)
		{
		if (isset($this->$name)) return $this->$name;
		}
	
	function __toString()
		{
		global $nails;

		if (!$this->html)
			{
			foreach($this->pages as $page)
				{
				$this->html .= '<li><a '.($page['alias'] == $nails['page_alias'] ? 'class="'.NAILS_NAV_CURRENTCLASS.'" ' : '').'href="'.$nails['base'].strtolower(($page['alias'] != NAILS_ALIAS_HOME ? Sanitise::html($page['alias']) : '')).'">'.Sanitise::html(str_replace('-', ' ', $page['menutext'])).'</a>'.$page['submenu'].'</li>';
				}
			}
		
		return '<ul>'.$this->html.'</ul>';
		}
	}

