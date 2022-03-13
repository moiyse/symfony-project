<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310085035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, likes_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, cout INT NOT NULL, likes INT DEFAULT NULL, INDEX IDX_351268BBC54C8C93 (type_id), INDEX IDX_351268BB2F23775F (likes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliotheque (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_4690D34D4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, user_id INT DEFAULT NULL, note_test DOUBLE PRECISION DEFAULT NULL, etat_candidature VARCHAR(255) DEFAULT NULL, message VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_E33BD3B84CC8505A (offre_id), INDEX IDX_E33BD3B8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, type_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, quiz_id INT DEFAULT NULL, etatchoix TINYINT(1) NOT NULL, contenu VARCHAR(255) NOT NULL, checked TINYINT(1) DEFAULT NULL, INDEX IDX_4F4880911E27F6BF (question_id), INDEX IDX_4F488091853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_commentaire DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, parent_id INT DEFAULT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5F9E962A71F7E88B (event_id), INDEX IDX_5F9E962A727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, siteweb VARCHAR(255) NOT NULL, secteur_entreprise VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, dat DATE NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, nb_participants INT NOT NULL, INDEX IDX_B26681EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, abonnement_id_id INT DEFAULT NULL, aimer INT NOT NULL, INDEX IDX_49CA4E7D9D86650F (user_id_id), INDEX IDX_49CA4E7DF483159E (abonnement_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, secteur VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, salaire DOUBLE PRECISION NOT NULL, datee_publication VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, INDEX IDX_AF86866FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, bibliotheque_id INT DEFAULT NULL, test_id INT NOT NULL, quiz_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, favori TINYINT(1) DEFAULT NULL, INDEX IDX_B6F7494E4419DE7D (bibliotheque_id), INDEX IDX_B6F7494E1E5D0459 (test_id), INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, test_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_A412FA921E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nbplace INT NOT NULL, eve_id INT NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, sujet_id INT DEFAULT NULL, candidature_id INT DEFAULT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, titre VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D87F7E0C7C4D497E (sujet_id), UNIQUE INDEX UNIQ_D87F7E0CB6121583 (candidature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, abonnement_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, etat VARCHAR(180) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, image VARCHAR(180) NOT NULL, googleAuthenticatorSecret VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A76ED395 (user_id), INDEX IDX_8D93D649F1D74413 (abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB2F23775F FOREIGN KEY (likes_id) REFERENCES likes (id)');
        $this->addSql('ALTER TABLE bibliotheque ADD CONSTRAINT FK_4690D34D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B84CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F4880911E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE choix ADD CONSTRAINT FK_4F488091853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A71F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DF483159E FOREIGN KEY (abonnement_id_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E4419DE7D FOREIGN KEY (bibliotheque_id) REFERENCES bibliotheque (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E1E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA921E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CB6121583 FOREIGN KEY (candidature_id) REFERENCES candidature (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DF483159E');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F1D74413');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E4419DE7D');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CB6121583');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A71F7E88B');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB2F23775F');
        $this->addSql('ALTER TABLE bibliotheque DROP FOREIGN KEY FK_4690D34D4CC8505A');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B84CC8505A');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F4880911E27F6BF');
        $this->addSql('ALTER TABLE choix DROP FOREIGN KEY FK_4F488091853CD175');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C7C4D497E');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E1E5D0459');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA921E5D0459');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBC54C8C93');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EA76ED395');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9D86650F');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A76ED395');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE bibliotheque');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE choix');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
    }
}
