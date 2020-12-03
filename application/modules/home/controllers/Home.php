<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * BlizzCMS
 *
 * An Open Source CMS for "World of Warcraft"
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2019, WoW-CMS
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author  WoW-CMS
 * @copyright  Copyright (c) 2017 - 2019, WoW-CMS.
 * @license https://opensource.org/licenses/MIT MIT License
 * @link    https://wow-cms.com
 * @since   Version 1.0.1
 * @filesource
 */

class Home extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('news/news_model');
        $this->load->config('home');

        if(!ini_get('date.timezone'))
           date_default_timezone_set($this->config->item('timezone'));

        if(!$this->wowgeneral->getMaintenance())
            redirect(base_url('maintenance'),'refresh');
    }

    public function index()
    {
        if ($this->config->item('migrate_status') == '1')
        {
            $data = array(
                'lang' => $this->lang->lang()
            );
            $this->load->view('migrate', $data);
        }
        else
        {
            $discord = $this->home_model->getDiscordInfo();

            $data = array(
                'pagetitle' => $this->lang->line('tab_home'),
                'slides' => $this->home_model->getSlides()->result(),
                'NewsList' => $this->news_model->getNewsList()->result(),
                'realmsList' => $this->wowrealm->getRealms()->result(),
                // Discord
                'discord_code' => $discord['code'],
                'discord_id' => $discord['guild']['id'],
                'discord_icon' => $discord['guild']['icon'],
                'discord_name' => $discord['guild']['name'],
                'discord_counts' => $discord['approximate_presence_count'],
            );

            $this->template->build('home', $data);
        }
    }

    public function migrateNow()
    {
        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        } else {
            redirect(base_url());
        }
    }

    public function setconfig()
    {
        $name = $this->input->post('name');
        $invitation = $this->input->post('invitation');
        $realmlist = $this->input->post('realmlist');
        $expansion = $this->input->post('expansion');
        $license = $this->input->post('license');
        echo $this->home_model->updateconfigs($name, $invitation, $realmlist, $expansion, $license);
    }
}
