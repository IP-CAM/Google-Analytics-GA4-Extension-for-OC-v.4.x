<?php
namespace Opencart\Admin\Controller\Extension\PsGoogleAnalytics\Analytics;
/**
 * Class PsGoogleAnalytics
 *
 * @package Opencart\Admin\Controller\Extension\PsGoogleAnalytics\Analytics
 */
class PsGoogleAnalytics extends \Opencart\System\Engine\Controller
{
    /**
     * @return void
     */
    public function index(): void
    {
        $this->load->language('extension/ps_google_analytics/analytics/ps_google_analytics');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/ps_google_analytics', 'user_token=' . $this->session->data['user_token'], true),
        ];


        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $data['action'] = $this->url->link('extension/ps_google_analytics/analytics/ps_google_analytics' . $separator . 'save', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics');

        $data['analytics_ps_google_analytics_status'] = (bool) $this->config->get('analytics_ps_google_analytics_status');
        $data['analytics_ps_google_analytics_google_tag_id'] = $this->config->get('analytics_ps_google_analytics_google_tag_id');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/ps_google_analytics/analytics/ps_google_analytics', $data));
    }

    public function save(): void
    {
        $this->load->language('extension/ps_google_analytics/analytics/ps_google_analytics');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_google_analytics/analytics/ps_google_analytics')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!isset($this->request->post['analytics_ps_google_analytics_google_tag_id'])) {
                $json['error']['input-gtm-id'] = $this->language->get('error_google_tag_id');
            } elseif (preg_match('/^G-[A-Z0-9]+$/', $this->request->post['analytics_ps_google_analytics_google_tag_id']) !== 1) {
                $json['error']['input-gtm-id'] = $this->language->get('error_google_tag_id_invalid');
            }
        }

        if (!$json) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('analytics_ps_google_analytics', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install(): void
    {

    }

    public function uninstall(): void
    {

    }
}