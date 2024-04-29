<?php

require '../../wp/wp-load.php';

$users = array(
	array(
		'first_name' => 'Aaron',
		'last_name'  => 'Hattenbach',
		'email'      => 'FakeAaronHattenbachEmail@kitces.com',
	),
	array(
		'first_name' => 'Adam',
		'last_name'  => 'Pearce',
		'email'      => 'fakeAdamPearce@kitces.com',
	),
	array(
		'first_name' => 'Amy',
		'last_name'  => 'Jones',
		'email'      => 'Amy@CompliantPerformance.com',
	),
	array(
		'first_name' => 'Amy',
		'last_name'  => 'McIlwain',
		'email'      => 'FakeAmyMcIlwainEmail@kitces.com',
	),
	array(
		'first_name' => 'Amy',
		'last_name'  => 'Parvaneh',
		'email'      => 'FakeAmyParvanehEmail@kitces.com',
	),
	array(
		'first_name' => 'Andrew',
		'last_name'  => 'Komarow',
		'email'      => 'fakeAndrew.Komarow@kitces.com',
	),
	array(
		'first_name' => 'Andrew',
		'last_name'  => 'McFadden',
		'email'      => 'amcfadden@panoramicfinancial.com',
	),
	array(
		'first_name' => 'Angie',
		'last_name'  => 'Herbers',
		'email'      => 'fakeAngie.Herbers@kitces.com',
	),
	array(
		'first_name' => 'Anna',
		'last_name'  => 'Rappaport',
		'email'      => 'anna@annarappaport.com',
	),
	array(
		'first_name' => 'Ashley',
		'last_name'  => 'Micciche',
		'email'      => 'ashleym@truenorthra.com',
	),
	array(
		'first_name' => 'Ashley',
		'last_name'  => 'Murphy',
		'email'      => 'ashley@arete-ws.com',
	),
	array(
		'first_name' => 'Barry D.',
		'last_name'  => 'Flagg',
		'email'      => 'FakeBarryFlagg@kitces.com',
	),
	array(
		'first_name' => 'Ben',
		'last_name'  => 'Coombs',
		'email'      => 'FakeBenCoombsEmail@kitces.com',
	),
	array(
		'first_name' => 'Ben',
		'last_name'  => 'Krueger',
		'email'      => 'fakebenkrueger@kitces.com',
	),
	array(
		'first_name' => 'Bill',
		'last_name'  => 'Winterberg',
		'email'      => 'fakeBillWinterberg@kitces.com',
	),
	array(
		'first_name' => 'Bob',
		'last_name'  => 'Seawright',
		'email'      => 'FakeBobSeawrightEmail@kitces.com',
	),
	array(
		'first_name' => 'Bob',
		'last_name'  => 'Veres',
		'email'      => 'bob@bobveres.com',
	),
	array(
		'first_name' => 'Caleb',
		'last_name'  => 'Brown',
		'email'      => 'FakeCalebBrownEmail@kitces.com',
	),
	array(
		'first_name' => 'Carl',
		'last_name'  => 'Richards',
		'email'      => 'carl@behaviorgap.com',
	),
	array(
		'first_name' => 'Charles',
		'last_name'  => 'Fox',
		'email'      => 'fakeCharlesFox@kitces.com',
	),
	array(
		'first_name' => 'Chris',
		'last_name'  => 'Hasting',
		'email'      => 'sales@panoramixfinancial.com',
	),
	array(
		'first_name' => 'Chris',
		'last_name'  => 'Stanley',
		'email'      => 'chris@beachstreetlegal.com',
	),
	array(
		'first_name' => 'Craig',
		'last_name'  => 'Breitsprecher',
		'email'      => 'fakeCraig.Breitsprecher@kitces.com',
	),
	array(
		'first_name' => 'Craig',
		'last_name'  => 'Iskowitz',
		'email'      => 'craig@ezragroupllc.com',
	),
	array(
		'first_name' => 'Dan',
		'last_name'  => 'Kellermeyer',
		'email'      => 'dan@newheightsfp.com',
	),
	array(
		'first_name' => 'Daniel',
		'last_name'  => 'Wrenne',
		'email'      => 'daniel.wrenne@gmail.com',
	),
	array(
		'first_name' => 'Daniel',
		'last_name'  => 'Zajac',
		'email'      => 'Daniel@simonezajac.com',
	),
	array(
		'first_name' => 'Dave',
		'last_name'  => 'Grant',
		'email'      => 'dave@retirementmattersillinois.com',
	),
	array(
		'first_name' => 'Dave',
		'last_name'  => 'Zoller',
		'email'      => 'dave@streamlinemypractice.com',
	),
	array(
		'first_name' => 'Derek',
		'last_name'  => 'Coburn',
		'email'      => 'FakeDerekCoburnEmail@kitces.com',
	),
	array(
		'first_name' => 'Derek',
		'last_name'  => 'Tharp',
		'email'      => 'derek@kitces.com',
	),
	array(
		'first_name' => 'Devin',
		'last_name'  => 'Carroll',
		'email'      => 'fakeDevinCarroll@kitces.com',
	),
	array(
		'first_name' => 'Duane',
		'last_name'  => 'Thompson',
		'email'      => 'FakeDuaneThompsonEmail@kitces.com',
	),
	array(
		'first_name' => 'Eugenie',
		'last_name'  => 'George',
		'email'      => 'FakeEugenieGeorgeEmail@kitces.com',
	),
	array(
		'first_name' => 'Garrett',
		'last_name'  => 'Philbin',
		'email'      => 'FakeGarrettPhilbinEmail@kitces.com',
	),
	array(
		'first_name' => 'Georgia',
		'last_name'  => 'Hussey',
		'email'      => 'hello@modernistfinancial.com',
	),
	array(
		'first_name' => 'Grier',
		'last_name'  => 'Rubeling',
		'email'      => 'Grier@advisortransitionservices.com',
	),
	array(
		'first_name' => 'Harry',
		'last_name'  => 'Sit',
		'email'      => 'FakeHarrySitEmail@kitces.com',
	),
	array(
		'first_name' => 'Jackson',
		'last_name'  => 'Rabuck',
		'email'      => 'jack@wcfinc.com',
	),
	array(
		'first_name' => 'Jake',
		'last_name'  => 'Thorkildsen',
		'email'      => 'jakethork@gmail.com',
	),
	array(
		'first_name' => 'Janki',
		'last_name'  => 'Patel',
		'email'      => 'FakeJankiPatelEmail@kitces.com',
	),
	array(
		'first_name' => 'Jay',
		'last_name'  => 'Mooreland',
		'email'      => 'fakejaymooreland@kitces.com',
	),
	array(
		'first_name' => 'Jean',
		'last_name'  => 'Sullivan',
		'email'      => 'fakeJeanSullivan@kitces.com',
	),
	array(
		'first_name' => 'Jeff',
		'last_name'  => 'Levine',
		'email'      => 'jeff@kitces.com',
	),
	array(
		'first_name' => 'Jeff',
		'last_name'  => 'Rose',
		'email'      => 'FakeJeffRoseEmail@kitces.com',
	),
	array(
		'first_name' => 'Joe',
		'last_name'  => 'Elsasser',
		'email'      => 'FakeJoeElsasserEmail@kitces.com',
	),
	array(
		'first_name' => 'John',
		'last_name'  => 'Grable',
		'email'      => 'grable@uga.edu',
	),
	array(
		'first_name' => 'Jon',
		'last_name'  => 'Yankee',
		'email'      => 'FakeJonYankeeEmail@kitces.com',
	),
	array(
		'first_name' => 'Joseph',
		'last_name'  => 'Pitzl',
		'email'      => 'joe@pitzlfinancial.com',
	),
	array(
		'first_name' => 'Julie',
		'last_name'  => 'Littlechild',
		'email'      => 'jlittlechild@absoluteengagement.com',
	),
	array(
		'first_name' => 'Justina',
		'last_name'  => 'Lai',
		'email'      => 'FakeJustinaLaiEmail@kitces.com',
	),
	array(
		'first_name' => 'Kathleen',
		'last_name'  => 'Boyd',
		'email'      => 'fakeKathleen.Boyd@kitces.com',
	),
	array(
		'first_name' => 'Katie',
		'last_name'  => 'Godbout',
		'email'      => 'FakeKatieGodbout@kitces.com',
	),
	array(
		'first_name' => 'Ken',
		'last_name'  => 'Solow',
		'email'      => 'FakeKenSolowEmail@kitces.com',
	),
	array(
		'first_name' => 'Kevin',
		'last_name'  => 'Dinino',
		'email'      => 'FakeKevinDininoEmail@kitces.com',
	),
	array(
		'first_name' => 'Lee',
		'last_name'  => 'Delahoussaye',
		'email'      => 'fakeLeeDelahoussaye@kitces.com',
	),
	array(
		'first_name' => 'Les',
		'last_name'  => 'Abromovitz',
		'email'      => 'labromovitz@ncsregcomp.com',
	),
	array(
		'first_name' => 'Liliya',
		'last_name'  => 'Jones',
		'email'      => 'FakeLiliyaJonesEmail@kitces.com',
	),
	array(
		'first_name' => 'Lindsay',
		'last_name'  => 'Bourkoff',
		'email'      => 'FakeLindsayBourkoff@kitces.com',
	),
	array(
		'first_name' => 'Lingke',
		'last_name'  => 'Wang',
		'email'      => 'lingke@ovidlife.com',
	),
	array(
		'first_name' => 'Maria',
		'last_name'  => 'Marsala',
		'email'      => 'eyb@ElevatingYourBusiness.com',
	),
	array(
		'first_name' => 'Mary',
		'last_name'  => 'Beth Storjohann',
		'email'      => 'marybeth@workablewealth.com',
	),
	array(
		'first_name' => 'Matt',
		'last_name'  => 'Cosgriff',
		'email'      => 'fakeMattCosgriff@kitces.com',
	),
	array(
		'first_name' => 'Matt',
		'last_name'  => 'Sonnen',
		'email'      => 'fakeMattSonnen@kitces.com',
	),
	array(
		'first_name' => 'Matthew',
		'last_name'  => 'Jarvis',
		'email'      => 'FakeMatthewJarvisEmail@kitces.com',
	),
	array(
		'first_name' => 'Meg',
		'last_name'  => 'Bartelt',
		'email'      => 'meg@flowfp.com',
	),
	array(
		'first_name' => 'Megan',
		'last_name'  => 'McCoy',
		'email'      => 'fakeMeganMcCoy@kitces.com',
	),
	array(
		'first_name' => 'Meghaan',
		'last_name'  => 'Lurtz',
		'email'      => 'meghaan@kitces.com',
	),
	array(
		'first_name' => 'Michael',
		'last_name'  => 'Kitces',
		'email'      => 'michael@kitces.com',
	),
	array(
		'first_name' => 'Michael',
		'last_name'  => 'Lecours',
		'email'      => 'fakeMichaelLecours@kitces.com',
	),
	array(
		'first_name' => 'Nathan',
		'last_name'  => 'Gehring',
		'email'      => 'FakeNathanGehringEmail@kitces.com',
	),
	array(
		'first_name' => 'Noah',
		'last_name'  => 'Morgan',
		'email'      => 'n.morgan@acornwa.com',
	),
	array(
		'first_name' => 'Patrick',
		'last_name'  => 'Cleary',
		'email'      => 'FakePatrickClearyEmail@kitces.com',
	),
	array(
		'first_name' => 'Philip',
		'last_name'  => 'Palaveev',
		'email'      => 'fakePhilipPalaveev@kitces.com',
	),
	array(
		'first_name' => 'Preeti',
		'last_name'  => 'Shah',
		'email'      => 'fakePreetiShah@kitces.com',
	),
	array(
		'first_name' => 'Rafael',
		'last_name'  => 'Bernard',
		'email'      => 'rbernard02@massmutual.com',
	),
	array(
		'first_name' => 'Rajiv',
		'last_name'  => 'Rebello',
		'email'      => 'rajiv.rebello@colvaservices.com',
	),
	array(
		'first_name' => 'Raoul',
		'last_name'  => 'Rodriguez',
		'email'      => 'rrodriguez@pinnacleadvisory.com',
	),
	array(
		'first_name' => 'Ria',
		'last_name'  => 'Boner',
		'email'      => 'FakeRiaBonerEmail@kitces.com',
	),
	array(
		'first_name' => 'Richard',
		'last_name'  => 'Durso',
		'email'      => 'RDurso@RTDFinancial.com',
	),
	array(
		'first_name' => 'Roger',
		'last_name'  => 'Whitney',
		'email'      => 'roger@wwkllc.com',
	),
	array(
		'first_name' => 'Ronald',
		'last_name'  => 'Sier',
		'email'      => 'FakeRonaldSierEmail@kitces.com',
	),
	array(
		'first_name' => 'Ryan',
		'last_name'  => 'Frailich',
		'email'      => 'ryan@deliberatefinances.com',
	),
	array(
		'first_name' => 'Sahil',
		'last_name'  => 'Vakil',
		'email'      => 'FakeSahilVakil@kitces.com',
	),
	array(
		'first_name' => 'Sean',
		'last_name'  => 'Brown',
		'email'      => 'fakeSeanBrown@kitces.com',
	),
	array(
		'first_name' => 'Shawn',
		'last_name'  => 'Tydlaska',
		'email'      => 'shawntyd@gmail.com',
	),
	array(
		'first_name' => 'Shelitha',
		'last_name'  => 'Smodic',
		'email'      => 'fakeShelithaSmodic@kitces.com',
	),
	array(
		'first_name' => 'Sophia',
		'last_name'  => 'Bera',
		'email'      => 'sophia@genyplanning.com',
	),
	array(
		'first_name' => 'Stacey',
		'last_name'  => 'McKinnon',
		'email'      => 'FakeStaceyMcKinnon@kitces.com',
	),
	array(
		'first_name' => 'Stephanie',
		'last_name'  => 'Bogan',
		'email'      => 'FakeStephanieBoganEmail@kitces.com',
	),
	array(
		'first_name' => 'Stephanie',
		'last_name'  => 'Sammons',
		'email'      => 'stephanie@sammonswealth.com',
	),
	array(
		'first_name' => 'Stephen',
		'last_name'  => 'Wershing',
		'email'      => 'FakeStephenWershingEmail@kitces.com',
	),
	array(
		'first_name' => 'Steve',
		'last_name'  => 'Starnes',
		'email'      => 'fakeSteveStarnes@kitces.com',
	),
	array(
		'first_name' => 'Susan',
		'last_name'  => 'Weiner',
		'email'      => 'FakeSusanWeinerEmail@kitces.com',
	),
	array(
		'first_name' => 'Ted',
		'last_name'  => 'Jenkin',
		'email'      => 'ted@oxygenfinancial.net',
	),
	array(
		'first_name' => 'Teresa',
		'last_name'  => 'Riccobuono',
		'email'      => 'teresa@simplyorganized.com',
	),
	array(
		'first_name' => 'Thusith',
		'last_name'  => 'Mahanama',
		'email'      => 'FakeThusithMahanamaEmail@kitces.com',
	),
	array(
		'first_name' => 'Tyler',
		'last_name'  => 'Olsen',
		'email'      => 'fakeTylerOlsen@kitces.com',
	),
	array(
		'first_name' => 'Vinicius',
		'last_name'  => 'Hiratuka',
		'email'      => 'fakeViniciusHiratuka@kitces.com',
	),
	array(
		'first_name' => 'Wade',
		'last_name'  => 'Pfau',
		'email'      => 'wadepfau@gmail.com',
	),
	array(
		'first_name' => 'William',
		'last_name'  => 'Bissett',
		'email'      => 'wbissett@pinnacleadvisory.com',
	),
	array(
		'first_name' => 'Zach',
		'last_name'  => 'McDonald',
		'email'      => 'FakeZachMcDonaldEmail@kitces.com',
	),
	array(
		'first_name' => 'Zach',
		'last_name'  => 'Obront',
		'email'      => 'FakeZachObrontEmail@kitces.com',
	),
);

foreach ( $users as $user ) {
	$user_info = get_user_by( 'email', $user['email'] );

	if ( $user_info ) {
		$user_id   = $user_info->ID;
		$user_data = new WP_User( $user_id );
		$user_data->add_role( 'guest_author' );
		wp_update_user(
			array(
				'ID'         => $user_id,
				'first_name' => $user['first_name'],
			)
		);
		wp_update_user(
			array(
				'ID'        => $user_id,
				'last_name' => $user['last_name'],
			)
		);
	}
}

echo 'ok';
