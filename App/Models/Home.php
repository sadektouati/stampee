<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Home extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAllVedetteAuctions()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT enchere.id, titre, commentaire, debut, fin, prix_plancher, offre_actuel, offre_actuel_membre, quantite_mise, a_coup_de_coeur_lord, est_enligne, delais_depasse, pas_commence, fichier FROM enchere join timbre on enchere.id=id_enchere left join image on (timbre.id=image.id_timbre and est_principale) where valide and est_principal and a_coup_de_coeur_lord");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function fake($what = '')
    {

        $buyers = 'Cayden Cherry
        Shyann Rose
        Tanner Delacruz
        Jermaine Buck
        Jewel Park
        Audrina Cuevas
        Urijah Orozco
        Aimee Smith
        Lilian Gilbert
        Kaleb Cervantes
        Jaelyn Bradford
        Esteban Burke
        Demarcus Wright
        Matthew Kent
        Madelynn Nielsen
        Leandro Hutchinson
        Alaina Martinez
        Marlie Morrison
        Yaretzi Holmes
        Justice Valenzuela
        Erika Weeks
        Alanna Palmer
        Alden Andrade
        Thomas Byrd
        Alani Adkins
        Ben Prince
        Valery Thornton
        Owen Terrell
        Bridget Holland
        Melanie Lowery
        Jacoby Hoover
        Giovanna Barnett
        Angela Orozco
        Gracelyn Peters
        Cristina Bowman
        Ronald Jordan
        Sophie Stokes
        Desirae Daniel
        Savion Walsh
        Iliana Melendez
        Taylor Rivas
        Abel Floyd
        Jose Stanton
        Carter Rocha
        Amelia Mercer
        Skyla Owen
        Kaelyn Flowers
        Brycen Short
        Haleigh Schneider
        Alma Mooney
        Anabelle Gould
        Abraham Butler
        Baylee DuranSimone Krueger
        Colby Cantu
        Diego Stevens
        Ethan Park
        Franco Leblanc
        Allison Riggs
        Madalynn Olson
        Frida Lozano
        Sabrina Bell
        Jovany Petty
        Aldo Morales
        Kaitlin Lamb
        Terrell Crawford
        Joaquin Mejia
        Jadyn Shields
        Kyan Leonard
        Leanna Stanley
        Sidney Benson
        Ruben Powers
        Melvin Owens
        Lexi Mcpherson
        Bria Wells
        Kaiden Kim
        Ray Hoffman
        Laila Mata
        Amira Abbott
        Ralph Cameron
        Marquise Stein
        Adalynn Baxter
        Kimora Alvarado
        Hunter Reese
        Jaida Huerta
        Bryce Macdonald
        Rihanna Crane
        Julian Landry
        Quinten Duarte
        Carmelo Browning
        Cristian Chase
        Mark Mcdowell
        Elise Buck';
        $buyersArray = explode('
        ', $buyers);

        $events = 'c.3500 BC Invention of the wheel and plough in Mesopotamia (present-day Iraq); invention of the sail in Egypt: three fundamental inventions for trade, agriculture and exploration.
        --c.3200 BC Invention of writing in Mesopotamia: the means to record and understand human history.
        --c.3000 BC Founding of the first cities in Sumeria (present-day Iraq): origin of modern social and administrative structures.
        --c.1600 BC Modern alphabet invented: the essential means of communication of complex concepts and culture.
        --c.1600 BC Beginning of Greek civilisation: essential to Western heritage and the root of mathematics, philosophy, political thinking and medicine.
        --753 BC Foundation of Rome: the Roman Empire is a pillar of the modern age, producing ideas on justice, law, engineering and warfare.
        --c.670 BC Invention of ironworking: metallurgy is the key to further technical, economic and military developments.
        --c.551 BC Birth of Confucius, the founder of one of the world’s major philosophical systems.
        --490 BC Battle of Marathon: the Greeks repel a Persian invasion, securing the survival of Greek culture and science.
        --486 BC Birth of Buddha, founder of one of the world’s major religions.
        --327 BC Empire of Alexander the Great reaches into India: the first example of a long-term and often violent interrelationship between Europe and Asia.
        --202 BC Hannibal is defeated by Rome: the victory is essential to secure the survival and expansion of Roman civilisation.
        --27 BC Founding of the Roman Empire: this is the start of the classic period of Roman domination in Europe and the Mediterranean.
        --c.5 BC Birth of Jesus Christ, founder of the many branches of Christianity. The exact date is disputed.
        --AD 105 First use of modern paper: this replaced stone, slate, papyrus and vellum as a cheap and convenient medium.
        --AD 280 Unification of China under the Western Chin dynasty creates the political shape of modern China.
        --AD 312 Roman Emperor Constantine converts to Christianity: this made it possible for Christianity to spread across Europe.
        --AD 476 Fall of the Roman Empire in the West ends 800 years of Roman hegemony. The creation of moderen Europe begins.
        --c.AD 570 Birth of Muhammad, founder of one of the world’s great religions.
        --c.AD 730 Printing invented in China: an essential step in mass communication/ administration/cultural dissemination.
        --AD 800 Charlemagne crowned Emperor of the new Western Empire. This marked the point at which Europe began to reintegrate. The Holy Roman Empire lasts for 1,000 years.
        --1054 Schism of Greek and Latin Christian Churches divides Christianity permanently into two geographical and denominational halves.
        --1088 First university founded in Bologna, Italy: the start of a modern conception of higher learning and universal knowledge.
        --1206 Genghis Khan begins his conquest of Asia. This has a major impact on Asian development and the movement of peoples.
        --1215 Magna Carta signed by King John at Runnymede: this is the origin of the modern concept of constitutional rule.
        --1453 Fall of Constantinople to the Ottoman Turks: Almost 500 years of Turkish domination of the Eastern Mediterranean, North Africa and the Middle East begins.
        --1455 First book printed with moveable type: Johannes Gutenberg’s revolution in printing technology makes mass-market reading possible.
        --1492 Christopher Columbus discovers the New World, bringing the Americas into a global trading/cultural system.
        --1509 Invention of the watch: essential to a modern economy and administration, this introduces the concept of regular timekeeping.
        --1517 Martin Luther launches the Reformation. It is the start of Protestant Christianity and the idea of religious individualism.
        --1519 Cortes begins his conquest of South America, which becomes part of the wider world economic and political system.
        --1564 William Shakespeare is born: his plays make fundamental statements about the human condition.
        --1651 Thomas Hobbess Leviathan is published: this is the origin of the modern idea of civil society, equality before the law and egoistic individualism]
        --1687 Isaac Newton publishes Principia Mathematica, the foundation of modern physics.
        --1776 American Declaration of Independence determines the political evolution of the New World and the rise of American power.
        --1789 French Revolution marks a fundamental break with the tradition of monarchy; the “rights of man” are enshrined.
        --1815 Battle of Waterloo: the Napoleonic Empire ends, and with it Napoleon’s ambition to rule and reform all of Europe.
        --1825 Rocket steam locomotive built, marking the start of the railway age of cheap, fast land transport.
        --1859 Publication of Darwin’s The Origin of Species. His theory of evolution transforms the view of Man and his environment, and belief in God.
        --1885 Benz develops first petrol-driven car, starting the most profound technical and social revolution of the modern age.
        --1893 New Zealand introduces unrestricted women’s suffrage. At this point women win the principle of full political equality.
        --1905 Einsteins theory of special relativity published. It transforms the nature of modern physical knowledge.
        --1917 Russian Revolution creates the first successful, long-term revolutionary state.
        --1918 End of the First World War. The Habsburg and Ottoman empires collapse; maps of Europe and the Middle East are redrawn.
        --1939 Outbreak of Second Worldd War: 50 million die worldwide from 1939-45 in the world’s largest and most deadly conflict, which ends the long age of imperialisms.
        --1945 End of Second World War; when the first nuclear bomb is detonated, mankind develops the means to destroy itself.
        --1949 Communist China founded: China is created as a single territorial unit with a common administration and a modernising economy.
        --1959 Invention of the silicon chip is the major technical invention of the past century, making possible the computer age.
        --1960 First contraceptive pill made available for women, who can now make their own biological choices about reproduction.
        --1989-90 Collapse of Communist regimes in Europe: marks the end of the long communist experiment; Asian communism is also transformed.';
        $eventsArray = explode('--', $events);
        $sellers = 'Kaiden Hines
        Kadin Pennington
        Damien Guzman
        Pablo Velasquez
        Stacy Meadows
        Quinn Li
        Angel Kane
        Jay Glenn
        Georgia Williams
        Joe Singh
        Jaidyn Robertson
        Wayne Benton
        Casey Montoya
        Holden Shea
        Samantha Gray
        Byron Wallace
        Anne Mora
        Scarlett Gibson
        Isabell Roman
        Thaddeus Baxter
        Freddy May
        Brett Esparza
        Caiden Camacho
        Alivia Mclean
        Rose Robbins
        Nathalie Mullins
        August Mayer
        Amari Rhodes
        Isabelle Golden
        Shannon Krause
        Roselyn Franco
        Drake Travis
        Christine Mccarty
        Amiyah Santana
        Janet Brock
        Laila Munoz
        Shyanne Rowland
        Kinsley Cordova
        Paul Day
        Braden Alexander
        Aydin Murphy
        Brogan Simmons
        Malik Burch
        Vaughn Esparza
        Oliver Castro
        Callie Warren
        Aubree Blevins';
        $sellersArray = explode('
        ', $sellers);

        
        $db = static::getDB();
        $stmt = $db->exec('delete from favoris; delete from mise; delete from image; delete from timbre; delete from enchere; delete from utilisateur');
        $stmt = $db->exec('ALTER TABLE favoris AUTO_INCREMENT = 1; ALTER TABLE mise AUTO_INCREMENT = 1; ALTER TABLE image AUTO_INCREMENT = 1; ALTER TABLE timbre AUTO_INCREMENT = 1; ALTER TABLE enchere AUTO_INCREMENT = 1; ALTER TABLE utilisateur AUTO_INCREMENT = 1');

        if($what == 'supprimer') return true;

        
        foreach ($sellersArray as $key => $nomutilisateur) {
            
            $motDePasseChifré = password_hash($nomutilisateur, PASSWORD_DEFAULT);
            $idutilisateur = \App\Models\Profile::insert([$nomutilisateur, $motDePasseChifré, strtolower(str_replace(' ', '_', $nomutilisateur) . '@server.email')]);
            
            $_SESSION['id_utilisateur'] = $idutilisateur;
            $nombreEncheres = random_int(0, 23);

            for ($k=0; $k <=$nombreEncheres ; $k++) {
                
                $timestamp = rand( strtotime("Dec 01 2022"), strtotime("May 30 2023") );
                $debut = date("Y-m-d", $timestamp );
                $fin = date("Y-m-d", strtotime($debut . ' +'. random_int(20, 60) .' day'));
                $countEvents = count($eventsArray)-1;
                $nomEnchere = $eventsArray[random_int(0, $countEvents)];
                $wordsArray = explode(' ', $nomEnchere);
                $countWords = count($wordsArray)-1;

                $nomEnchere = substr($wordsArray[random_int(0, $countWords)] . ' ' . $wordsArray[random_int(0, $countWords)] . ' ' . $wordsArray[random_int(0, $countWords)]. ' ' . $wordsArray[random_int(0, $countWords)], 0, 45);
                $description = $eventsArray[random_int(0, $countEvents)] . $eventsArray[random_int(0, $countEvents)];

                $identifiantEnchere = \App\Models\Enchere::upsert([null, ucwords($nomEnchere), $debut, $fin, random_int(10, 50000), random_int(0,1)==1?1:0, $description, $_SESSION['id_utilisateur']]);

                    $nombreTimbres = random_int(1, 5);
                    for ($i=0; $i <=$nombreTimbres ; $i++) {
                        $countEvents = count($eventsArray)-1;
                        $nomTimbre = $eventsArray[random_int(0, $countEvents)];
                        $wordsArray = explode(' ', $nomTimbre);
                        $countWords = count($wordsArray)-1;

                        $nomTimbre = substr($wordsArray[random_int(0, $countWords)] . ' ' . $wordsArray[random_int(0, $countWords)] . ' ' . $wordsArray[random_int(0, $countWords)]. ' ' . $wordsArray[random_int(0, $countWords)], 0, 45);
                        
                        $identifiantEnchere;

                        $identifiantTimbre = \App\Models\Timbre::upsert([null, ucfirst($nomTimbre), random_int(1, 50000), random_int(3, 20), random_int(3, 20), random_int(1, 3)==2?1:0, $identifiantEnchere, random_int(1, 4), random_int(1, 4), random_int(1, 6), $i==0?1:0, random_int(1500, 2015), $identifiantEnchere, $_SESSION['id_utilisateur'], null, null, null, null, null, $identifiantEnchere, random_int(1, 4), random_int(1, 4), random_int(1, 6), $i==0?1:0, random_int(1500, 2015)]);
                    }
                
            }

        }


        $db = static::getDB();
        $stmt = $db->query("SELECT count(*) as total from enchere");
        $countEncheres = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        foreach ($buyersArray as $key => $nomutilisateur) {
            $motDePasseChifré = password_hash($nomutilisateur, PASSWORD_DEFAULT);
            $idutilisateur = \App\Models\Profile::insert([$nomutilisateur, $motDePasseChifré, strtolower(str_replace(' ', '_', $nomutilisateur) . '@server.email')]);
            $_SESSION['id_utilisateur'] = $idutilisateur;


            $nombreEncheres = random_int(1, 15);
            for ($i=0; $i <=$nombreEncheres ; $i++) {
                $idutilisateur = \App\Models\Favoris::insert(random_int(1, $countEncheres), $_SESSION['id_utilisateur']);   
            }

            $nombreEncheres = random_int(1, 200);
            for ($i=0; $i <=$nombreEncheres ; $i++) {
                $encheresFavoris = \App\Models\Fiche::upsertBid([$_SESSION['id_utilisateur'], random_int(1, $countEncheres), random_int(10000, 99999), null, $_SESSION['id_utilisateur'], random_int(1, $countEncheres), random_int(10000, 99999)]);
            }

        }

        $db = static::getDB();
        $stmt = $db->query("update enchere set a_coup_de_coeur_lord=(id%6)=3");
        return true;
    }

}
