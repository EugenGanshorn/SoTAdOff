<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180630202316 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE device ADD COLUMN visible INTEGER UNSIGNED DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__device AS SELECT id, name, ip_address, username, password, position, module, friendly_name, topic, button_topic, power, power_on_state, led_state, save_data, save_state, button_retain, power_retain, prm_baudrate, prm_group_topic, prm_ota_url, prm_restart_reason, prm_sleep, prm_boot_count, prm_save_count, prm_save_address, fwr_version, fwr_build_date_time, fwr_boot, log_serial_log, log_web_log, log_sys_log, log_log_host, log_log_port, log_tele_period, net_hostname, net_ip_address, net_gateway, net_subnetmask, net_dns_server, net_mac, net_webserver, net_wifi_config, mqtt_host, mqtt_port, mqtt_client_mask, mqtt_client, mqtt_user, mqtt_type, sts_time, sts_uptime, sts_vcc, sts_power, sts_dimmer, sts_color, sts_hsbcolor, sts_ct, sts_scheme, sts_fade, sts_speed, sts_led_table, wifi_ap, wifi_ssid, wifi_rssi, wifi_ap_mac FROM device');
        $this->addSql('DROP TABLE device');
        $this->addSql('CREATE TABLE device (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(255) DEFAULT \'\' NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, position INTEGER UNSIGNED DEFAULT 0 NOT NULL, module INTEGER DEFAULT NULL, friendly_name VARCHAR(255) DEFAULT NULL, topic VARCHAR(255) DEFAULT NULL, button_topic INTEGER DEFAULT NULL, power INTEGER DEFAULT NULL, power_on_state INTEGER DEFAULT NULL, led_state INTEGER DEFAULT NULL, save_data INTEGER DEFAULT NULL, save_state INTEGER DEFAULT NULL, button_retain INTEGER DEFAULT NULL, power_retain INTEGER DEFAULT NULL, prm_baudrate INTEGER DEFAULT NULL, prm_group_topic VARCHAR(255) DEFAULT NULL, prm_ota_url VARCHAR(255) DEFAULT NULL, prm_restart_reason VARCHAR(255) DEFAULT NULL, prm_sleep INTEGER DEFAULT NULL, prm_boot_count INTEGER DEFAULT NULL, prm_save_count INTEGER DEFAULT NULL, prm_save_address VARCHAR(255) DEFAULT NULL, fwr_version VARCHAR(255) DEFAULT NULL, fwr_build_date_time VARCHAR(255) DEFAULT NULL, fwr_boot INTEGER DEFAULT NULL, log_serial_log INTEGER DEFAULT NULL, log_web_log INTEGER DEFAULT NULL, log_sys_log INTEGER DEFAULT NULL, log_log_host VARCHAR(255) DEFAULT NULL, log_log_port INTEGER DEFAULT NULL, log_tele_period INTEGER DEFAULT NULL, net_hostname VARCHAR(255) DEFAULT NULL, net_ip_address VARCHAR(255) DEFAULT NULL, net_gateway VARCHAR(255) DEFAULT NULL, net_subnetmask VARCHAR(255) DEFAULT NULL, net_dns_server VARCHAR(255) DEFAULT NULL, net_mac VARCHAR(255) DEFAULT NULL, net_webserver INTEGER DEFAULT NULL, net_wifi_config INTEGER DEFAULT NULL, mqtt_host VARCHAR(255) DEFAULT NULL, mqtt_port INTEGER DEFAULT NULL, mqtt_client_mask VARCHAR(255) DEFAULT NULL, mqtt_client VARCHAR(255) DEFAULT NULL, mqtt_user VARCHAR(255) DEFAULT NULL, mqtt_type INTEGER DEFAULT NULL, sts_time VARCHAR(255) DEFAULT NULL, sts_uptime VARCHAR(255) DEFAULT NULL, sts_vcc DOUBLE PRECISION DEFAULT NULL, sts_power INTEGER DEFAULT NULL, sts_dimmer INTEGER DEFAULT NULL, sts_color VARCHAR(255) DEFAULT NULL, sts_hsbcolor VARCHAR(255) DEFAULT NULL, sts_ct INTEGER DEFAULT NULL, sts_scheme INTEGER DEFAULT NULL, sts_fade INTEGER DEFAULT NULL, sts_speed INTEGER DEFAULT NULL, sts_led_table INTEGER DEFAULT NULL, wifi_ap INTEGER DEFAULT NULL, wifi_ssid VARCHAR(255) DEFAULT NULL, wifi_rssi INTEGER DEFAULT NULL, wifi_ap_mac VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO device (id, name, ip_address, username, password, position, module, friendly_name, topic, button_topic, power, power_on_state, led_state, save_data, save_state, button_retain, power_retain, prm_baudrate, prm_group_topic, prm_ota_url, prm_restart_reason, prm_sleep, prm_boot_count, prm_save_count, prm_save_address, fwr_version, fwr_build_date_time, fwr_boot, log_serial_log, log_web_log, log_sys_log, log_log_host, log_log_port, log_tele_period, net_hostname, net_ip_address, net_gateway, net_subnetmask, net_dns_server, net_mac, net_webserver, net_wifi_config, mqtt_host, mqtt_port, mqtt_client_mask, mqtt_client, mqtt_user, mqtt_type, sts_time, sts_uptime, sts_vcc, sts_power, sts_dimmer, sts_color, sts_hsbcolor, sts_ct, sts_scheme, sts_fade, sts_speed, sts_led_table, wifi_ap, wifi_ssid, wifi_rssi, wifi_ap_mac) SELECT id, name, ip_address, username, password, position, module, friendly_name, topic, button_topic, power, power_on_state, led_state, save_data, save_state, button_retain, power_retain, prm_baudrate, prm_group_topic, prm_ota_url, prm_restart_reason, prm_sleep, prm_boot_count, prm_save_count, prm_save_address, fwr_version, fwr_build_date_time, fwr_boot, log_serial_log, log_web_log, log_sys_log, log_log_host, log_log_port, log_tele_period, net_hostname, net_ip_address, net_gateway, net_subnetmask, net_dns_server, net_mac, net_webserver, net_wifi_config, mqtt_host, mqtt_port, mqtt_client_mask, mqtt_client, mqtt_user, mqtt_type, sts_time, sts_uptime, sts_vcc, sts_power, sts_dimmer, sts_color, sts_hsbcolor, sts_ct, sts_scheme, sts_fade, sts_speed, sts_led_table, wifi_ap, wifi_ssid, wifi_rssi, wifi_ap_mac FROM __temp__device');
        $this->addSql('DROP TABLE __temp__device');
    }
}
