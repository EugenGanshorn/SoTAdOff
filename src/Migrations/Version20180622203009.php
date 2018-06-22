<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180622203009 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__device AS SELECT id, name, ip_address, username, password, position FROM device');
        $this->addSql('DROP TABLE device');
        $this->addSql('CREATE TABLE device (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, ip_address VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE BINARY, username VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) DEFAULT NULL COLLATE BINARY, position INTEGER UNSIGNED DEFAULT 0 NOT NULL, module INTEGER DEFAULT NULL, friendly_name VARCHAR(255) DEFAULT NULL, topic VARCHAR(255) DEFAULT NULL, button_topic INTEGER DEFAULT NULL, power INTEGER DEFAULT NULL, power_on_state INTEGER DEFAULT NULL, led_state INTEGER DEFAULT NULL, save_data INTEGER DEFAULT NULL, save_state INTEGER DEFAULT NULL, button_retain INTEGER DEFAULT NULL, power_retain INTEGER DEFAULT NULL, prm_baudrate INTEGER DEFAULT NULL, prm_group_topic VARCHAR(255) DEFAULT NULL, prm_ota_url VARCHAR(255) DEFAULT NULL, prm_restart_reason VARCHAR(255) DEFAULT NULL, prm_sleep INTEGER DEFAULT NULL, prm_boot_count INTEGER DEFAULT NULL, prm_save_count INTEGER DEFAULT NULL, prm_save_address VARCHAR(255) DEFAULT NULL, fwr_version VARCHAR(255) DEFAULT NULL, fwr_build_date_time VARCHAR(255) DEFAULT NULL, fwr_boot INTEGER DEFAULT NULL, log_serial_log INTEGER DEFAULT NULL, log_web_log INTEGER DEFAULT NULL, log_sys_log INTEGER DEFAULT NULL, log_log_host VARCHAR(255) DEFAULT NULL, log_log_port INTEGER DEFAULT NULL, log_tele_period INTEGER DEFAULT NULL, net_hostname VARCHAR(255) DEFAULT NULL, net_ip_address VARCHAR(255) DEFAULT NULL, net_gateway VARCHAR(255) DEFAULT NULL, net_subnetmask VARCHAR(255) DEFAULT NULL, net_dns_server VARCHAR(255) DEFAULT NULL, net_mac VARCHAR(255) DEFAULT NULL, net_webserver INTEGER DEFAULT NULL, net_wifi_config INTEGER DEFAULT NULL, mqtt_host VARCHAR(255) DEFAULT NULL, mqtt_port INTEGER DEFAULT NULL, mqtt_client_mask VARCHAR(255) DEFAULT NULL, mqtt_client VARCHAR(255) DEFAULT NULL, mqtt_user VARCHAR(255) DEFAULT NULL, mqtt_type INTEGER DEFAULT NULL, sts_time VARCHAR(255) DEFAULT NULL, sts_uptime VARCHAR(255) DEFAULT NULL, sts_vcc DOUBLE PRECISION DEFAULT NULL, sts_power INTEGER DEFAULT NULL, wifi_ap INTEGER DEFAULT NULL, wifi_ssid VARCHAR(255) DEFAULT NULL, wifi_rssi INTEGER DEFAULT NULL, wifi_ap_mac VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO device (id, name, ip_address, username, password, position) SELECT id, name, ip_address, username, password, position FROM __temp__device');
        $this->addSql('DROP TABLE __temp__device');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__device AS SELECT id, name, ip_address, username, password, position FROM device');
        $this->addSql('DROP TABLE device');
        $this->addSql('CREATE TABLE device (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, ip_address VARCHAR(255) DEFAULT \'\' NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, position INTEGER UNSIGNED DEFAULT 0 NOT NULL, status CLOB DEFAULT NULL COLLATE BINARY --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO device (id, name, ip_address, username, password, position) SELECT id, name, ip_address, username, password, position FROM __temp__device');
        $this->addSql('DROP TABLE __temp__device');
    }
}
