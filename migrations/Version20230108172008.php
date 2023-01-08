<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108172008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quest_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quest_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quest (id INT NOT NULL, created_by_id INT NOT NULL, quest_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, location VARCHAR(255) DEFAULT NULL, number_of_participants INT NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, starts_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_finished BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4317F817B03A8386 ON quest (created_by_id)');
        $this->addSql('CREATE INDEX IDX_4317F817916B31D8 ON quest (quest_type_id)');
        $this->addSql('CREATE TABLE quest_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, status BOOLEAN NOT NULL, signedup_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE user_quest (user_id INT NOT NULL, quest_id INT NOT NULL, PRIMARY KEY(user_id, quest_id))');
        $this->addSql('CREATE INDEX IDX_A1D5034FA76ED395 ON user_quest (user_id)');
        $this->addSql('CREATE INDEX IDX_A1D5034F209E9EF4 ON user_quest (quest_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F817B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F817916B31D8 FOREIGN KEY (quest_type_id) REFERENCES quest_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034F209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quest_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quest_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE quest DROP CONSTRAINT FK_4317F817B03A8386');
        $this->addSql('ALTER TABLE quest DROP CONSTRAINT FK_4317F817916B31D8');
        $this->addSql('ALTER TABLE user_quest DROP CONSTRAINT FK_A1D5034FA76ED395');
        $this->addSql('ALTER TABLE user_quest DROP CONSTRAINT FK_A1D5034F209E9EF4');
        $this->addSql('DROP TABLE quest');
        $this->addSql('DROP TABLE quest_type');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_quest');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
