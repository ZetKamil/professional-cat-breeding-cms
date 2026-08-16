<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Artykuły redakcyjne dla hodowli kotów rasowych ZetKamil.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();
        $categories = Category::query()->pluck('id')->all();

        if (empty($users) || empty($categories)) {
            return;
        }

        // Czyszczenie starej bazy postów (idempotentność)
        DB::table('category_post')->delete();
        DB::table('posts')->delete();

        $posts = [
            [
                'title' => 'Niewidzialne dziedzictwo: Dlaczego badania genetyczne HCM i PKD to najpiękniejsza obietnica dla Twojej rodziny',
                'excerpt' => 'Prawdziwy luksus to nie tylko aksamitne futro czy bursztynowe spojrzenie. To bezcenny spokój ducha na 15–20 lat wspólnego życia. Odkryj, jak naukowa precyzja i etyczna hodowla chronią serce i zdrowie Twojego przyszłego przyjaciela.',
                'body' => "W gabinecie weterynaryjnym panuje absolutna cisza. Słychać jedynie miarowy, rytmiczny dźwięk aparatu ultrasonograficznego. Na ekranie widać pulsujące serce kotki hodowlanej – silne, symetryczne, bez cienia pogrubienia ścian komór. Lekarz spogląda na nas z uśmiechem i wypowiada słowa, na które czekamy przed każdym miotem: „Serce jest perfekcyjne”.\n\nDla kogoś z zewnątrz to tylko specjalistyczne badanie echokardiograficzne. Dla nas w hodowli z Mazowieckiej Szwajcarii to fundament wszystkiego, co robimy. To moment, w którym nauka spotyka się z odpowiedzialnością za żywą istotę.\n\n### Co kryje się w cieniu genów? HCM i PKD bez tajemnic\nKardiomiopatia przerostowa (HCM) oraz wielotorbielowatość nerek (PKD) to podstępne schorzenia genetyczne, które przez lata potrafią nie dawać żadnych objawów zewnętrznych. Kot może wyglądać na okaz zdrowia – ma lśniące futro, żywe spojrzenie i niespożytą energię. Jednak w jego wnętrzu kod DNA może nieść cichy wyrok, który ujawnia się dopiero w dojrzałym wieku, przynosząc opiekunom niewyobrażalny ból.\n\nHCM powoduje stopniowe patologiczne pogrubienie mięśnia sercowego, co z czasem prowadzi do niewydolności krążenia. PKD z kolei prowadzi do powstawania cyst w miąższu nerek, niszcząc ten kluczowy narząd organizmu. Obie choroby mają podłoże uwarunkowane genetycznie i mogą być przekazywane z pokolenia na pokolenie.\n\n### Dlaczego testy genetyczne rodziców to absolutna konieczność?\nW naszej hodowli zasada jest prosta i niepodważalna: do programu hodowlanego kwalifikujemy wyłącznie osobniki wolne od mutacji genetycznych odpowiedzialnych za HCM i PKD, co potwierdzamy certyfikatami z renomowanych europejskich laboratoriów genetycznych (m.in. Laboklin i Langford Vets).\n\nGdy wybierasz kocię z naszej hodowli, nie kupujesz jedynie pięknego rasowego malucha. Otrzymujesz pisemną gwarancję, że jego rodzice zostali przebadani wzdłuż i wszerz. Przegląd kardiologiczny, genetyczny oraz profil lipidowy to nasza obietnica, że Twój nowy domownik spędzi z Tobą 15, a nawet 20 długich, szczęśliwych lat bez przewlekłego cierpienia.\n\n### Standard premium to spokój ducha\nDla nas luksus nie oznacza złotych miseczek czy drogich gadżetów. Prawdziwy luksus w świecie kotów rasowych to wiedza, że zrobiliśmy absolutnie wszystko, co w mocy nowoczesnej medycyny weterynaryjnej, aby dać Twojemu kociakowi najzdrowszy możliwy start w życie.",
                'is_published' => true,
                'category_names' => ['Zdrowie i Genetyka'],
                'image_url' => 'https://images.unsplash.com/photo-1576201836106-db1758fd1c97?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Sztuka kreowania domowego sanktuarium: Kompletny przewodnik po wyprawce premium dla kocięcia',
                'excerpt' => 'Pierwsze 48 godzin w nowym domu decyduje o poczuciu bezpieczeństwa kocięcia na całe życie. Dowiedz się, jak stworzyć przestrzeń pełną harmonii, naturalnych materiałów i zapachów, które bezstresowo wprowadzą malucha do Twojego świata.',
                'body' => "Drzwi transportera otwierają się cicho. Mały maluch z wielkimi, ciekawskimi oczami stawia swój pierwszy ostrożny krok na Twoim parkiecie. Wokół panuje cisza, a w powietrzu unosi się zapach nowego miejsca. To magiczny moment – sekunda, w której obcy budynek staje się dla niego nowym domem.\n\nJako hodowcy wiemy, że pierwsze dni po zmianie otoczenia są kluczowe dla emocjonalnego rozwoju kocięcia. Właściwie skompletowana wyprawka to nie lista zakupowa z marketu – to architektura bezpieczeństwa, która zamienia niepewność w bezgraniczne zaufanie i ciche głośne mruczenie na Twoich kolanach.\n\n### 1. Drapak jak rzeźba: Naturalny syzal i drewno zamiast sztucznego pluszu\nKot nie drapie mebli z złośliwości – to jego naturalny rytuał rozciągania kręgosłupa, znaczenia terytorium oraz zrzucania martwych łusek pazurków. Zapomnij o małych, chwiejnych drapakach z syntetycznym materiałem.\n\nWybierz stabilny konstrukcyjnie drapak sięgający minimum 100-120 cm wysokości, oplatany grubym, naturalnym sznurem syzalowym (min. 10 mm średnicy) z ciężką podstawą z litego drewna. Kot musi móc wyciągnąć się na pełną długość ciała bez ryzyka, że mebel się chwieje.\n\n### 2. Ergonomia miseczek: Ceramika i stal nierdzewna przeciw zmęczeniu wibrysów\nCzy wiesz, że koty posiadają zjawisko zwane „zmęczeniem wibrysów” (whisker fatigue)? Wąsy kota to niezwykle czułe narządy dotyku z tysiącami zakończeń nerwowych. Gdy uderzają o wąskie i głębokie ścianki plastikowej miski, zwierzę odczuwa stały dyskomfort.\n\nRekomendujemy szerokie, płaskie talerze ceramiczne lub ze stali chirurgicznej. Ceramika chroni dodatkowo przed tzw. trądzikiem kocim (baterie nie wnikają w pory materiału) oraz zapewnia łatwość utrzymania krystalicznej czystości.\n\n### 3. Kuweta i podłoże: Kontynuacja nawyków z hodowli\nW naszej hodowli maluchy od 4. tygodnia życia uczą się korzystania z otwartych oraz zakrytych kuwet z bezpyłowym, naturalnym podłożem bentonitowym lub drobnym żwirkiem drewnianym. Aby uniknąć stresu adaptacyjnego, przez pierwsze dwa tygodnie w nowym domu zastosuj dokładnie ten sam żwirek, z którego kocię korzystało u nas – zapach i faktura pod łapkami dadzą mu natychmiastowe poczucie znajomej bezpiecznej przystani.\n\n### 4. Zabawki sensoryczne i strefa wypoczynku\nUnikaj plastikowych zabawek z ostrymi krawędziami. Najlepszym wyborem są wędki z naturalnymi piórami marabuta, zabawki z matatabi lub kocimiętką kanadyjską oraz miękkie legowiska typu „donut” umieszczone na podwyższeniu, z których kociak może obserwować domowe życie z bezpiecznej perspektywy.",
                'is_published' => true,
                'category_names' => ['Wyprawka i Pielęgnacja', 'Socjalizacja i Wychowanie'],
                'image_url' => 'https://images.unsplash.com/photo-1533738363-b7f9aef128ce?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Żywy obraz w Twoim salonie: Magia, temperament i niespożyta energia Kota Bengalskiego',
                'excerpt' => 'Wygląd dzikiego lamparda z sercem oddanego domowego przyjaciela. Poznaj niezwykłą naturę kotów bengalskich – rasy, która wnosi do domu elegancję, niesamowitą inteligencję i odrobinę dzikiej przygody.',
                'body' => "Przez Twój salon przemyka złocisty cień. Słońce wpada przez okno, odbijając się od futra o jedwabistym połysku zwanym „glitter” – unikalnym efekcie, jakby sierść kota została posypana mikroskopijnym złotym pyłem. Na jego boku odznaczają się głębokie, trójkolorowe rozety przypominające ubarwienie dzikiego ocelota. Kot zatrzymuje się, spogląda na Ciebie mądrymi, zielonymi oczami... i wskakuje Ci prosto na kolana, domagając się czułego drapania za uchem.\n\nOto Kot Bengalski. Jedna z najbardziej fascynujących i spektakularnych ras na świecie, będąca owocem połączenia dzikiego kota azjatyckiego (Prionailurus bengalensis) z kotem domowym.\n\n### Dziki wygląd, udomowione serce\nCzęstym mit narosłym wokół kotów bengalskich jest przekonanie, że ich dziki wygląd idzie w parze z nieokiełznanym charakterem. Nic bardziej mylnego. Współczesny kot bengalski pochodzący z legalnej, profesjonalnej hodowli z wielopokoleniowym rodowodem to pieszczoch o wybitnym poziomie przywiązania do czlowieka.\n\nBengale kochają towarzystwo swojej rodziny. Chodzą za opiekunem krok w krok, biorą czynny udział w gotowaniu, pracy przy komputerze czy wieczornym relaksie na kanapie. Ich głos jest niezwykle bogaty – od cichego gruchania przypominającego ptasi śpiew, po głośne mruczenie.\n\n### Inteligencja, która zaskakuje każdego dnia\nBengal to kot dla osób, które szukają w domu aktywnego, myślącego partnera. Ich inteligencja graniczy z niesamowitością. Z łatwością uczą się aportować lekkie piłeczki, otwierać drzwi z klamki, a nawet korzystać z dedykowanych kół do biegania dla kotów.\n\nFascynującą cechą bengali jest również ich zamiłowanie do... wody! Nie zdziw się, gdy Twój bengalskis przyjacielskis wskoczy do wanny pełnej piany lub będzie z fascynacją łapał krople spływające z kranu w łazience.\n\n### Jak stworzyć idealne środowisko dla Bengala?\nAby Kot Bengalski był w pełni szczęśliwy, potrzebuje stymulacji umysłowej oraz możliwości pionowej wspinaczki. Wysokie półki ścienne, drapaki sięgające sufitu i codzienne, 15-minutowe sesje zabawy z wędką sprawią, że Twój domowy „lampard” będzie najspokojniejszym i najbardziej zrelaksowanym towarzyszem na świecie.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy'],
                'image_url' => 'https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Arystokratyczny spokój w aksamitnym futrze: Dlaczego Kot Brytyjski to kwintesencja domowej harmonii',
                'excerpt' => 'Gęste, miłe w dotyku futro niczym pluszowy niedźwiadek, stoicki spokój i miedziane spojrzenie pełne mądrości. Odkryj, dlaczego Kot Brytyjski Krótkowłosy uważany jest za najbardziej zrównoważonego przyjaciela całej rodziny.',
                'body' => "Wracasz do domu po intensywnym, stresującym dniu w pracy. Przekraczasz próg, a na fotelu czeka on – masywny, niedźwiedzi kot o okrągłej głowie, pyzatych policzkach i głębokich, miedziano-bursztynowych oczach. Nie biega chaotycznie, nie hałasuje. Powoli wstaje, przeciąga się z niesamowitym wdziękiem i podchodzi do Twoich nóg, opierając swoje gęste, miękkie futro o Twoją dłoń. Czas natychmiast zwalnia.\n\nKot Brytyjski Krótkowłosy (British Shorthair) to prawdziwy arystokrata światowego felinologicznego świata. Jego obecność działa na domowników jak balsam – wnosi ład, ciszę i ukojenie.\n\n### Pluszowy niedźwiadek o sercu ze złota\nCharakterystyczną cechą rasy brytyjskiej jest unikalna struktura okrywy włosowej. Sierść brytyjczyka jest krótka, gęsta, sprężysta i niezwykle miękka w dotyku dzięki obfitemu podbiciu podszerstkiem. Dotknięcie brytyjskiego kota przypomina głaskanie luksusowego, wełnianego kaszmiru.\n\nJednak to nie wygląd, lecz zrównoważony temperament czyni z tej rasy tak wybitnego towarzysza. Brytyjczycy to koty niezwykle wyrozumiałe, cierpliwe i pozbawione agresji. Doskonale odnajdują się w domach z dziećmi, zachowując stoicki spokój nawet w trudniejszych sytuacjach.\n\n### Etykieta i niezależność: Kot, który szanuje Twoją przestrzeń\nW przeciwieństwie do ras bardzo wymagających stałej uwagi, Brytyjczyk potrafi doskonale organizować sobie czas pod nieobecność opiekuna. Kiedy pracujesz w biurze, on smacznie śpi na swoim ulubionym parawanie lub obserwuje ptaki za oknem.\n\nGdy wracasz, towarzyszy Ci nienachalnie – woli leżeć obok Ciebie na fotelu lub na oparciu kanapy, dając Ci poczucie stałej, czułej bliskości bez natarczywości. To idealny wybór dla osób zapracowanych, cennych elegancję i harmonię.\n\n### Długowieczność i potężne zdrowie\nBrytyjczycy to koty silne, o krępej kośćcu i mocnym układzie odpornościowym. W naszej hodowli dbamy o prawidłową linię genetyczną, co sprawia, że nasi podopieczni cieszą się doskonałym zdrowiem przez długie lata, stając się nieodłączną częścią domowej historii kilku pokoleń.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy', 'Socjalizacja i Wychowanie'],
                'image_url' => 'https://images.unsplash.com/photo-1573865526739-10659fec78a5?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Szafirowe spojrzenie i czuły dialog: Niezwykła więź, jaką tworzy z człowiekiem Kot Syjamski',
                'excerpt' => 'Ekspresyjne, głębokie oczy w kolorze szafiru, aksamitna sierść z eleganckimi znaczeniami point i miłość tak wielka, że staje się Twoim drugim cieniem. Poznaj poetycką naturę Kotów Syjamskich.',
                'body' => "Gdy spoglądasz w oczy Kota Syjamskiego, nie patrzysz na zwykłe zwierzę domowe. Patrzysz w dwa głębokie, bezdenne szafiry, w których odbija się tysiącletnia historia wschodnich świątyń Syjamu. Syjam nie przebywa w Twoim domu obok Ciebie – on żyje Tobą, każdą Twoją emocją i każdym Twoim krokiem.\n\nJeśli szukasz kota, który stworzy z Tobą unikalną, wręcz metafizyczną więź pełną wzajemnego zrozumienia i czułości, rasa syjamska jest wyborem bezapelacyjnym.\n\n### Oczy, które widzą Twoją duszę\nSmukła, atletyczna sylwetka, kontrastujące ciemne znaczenia na pyszczku, uszach, łapkach i ogonie (tzw. ubarwienie point) oraz krystaliczne, intensywnie błękitne spojrzenie – Kot Syjamski to żywy symbol gracji i klasycznej elegancji. Ich futro jest pozbawione podszerstka, przylegające gładko do ciała i jedwabiste w dotyku.\n\n### Głos, który opowiada historie: Słynna melodia Syjama\nKoty syjamskie słyną ze swojej niesamowitej komunikatywności. Syjam nie używa zwykłego mruczenia – on prowadzi z opiekunem prawdziwy dialog. Odpowiada na Twoje pytania melodyjnym, cichym głosem o różnorodnej modulacji. Opowiada Ci, jak minął mu dzień, wita Cię radosnym okrzykiem przy drzwiach i delikatnie przypomina, że nadeszła pora na wieczorną porcję pieszczot.\n\n### Najwierniejszy cień i empata domowego ogniska\nSyjamy to koty o wybitnym poziomie empatii. Z łatwością wyczuwają nastrój swojego opiekuna – gdy jesteś smutny lub zmęczony, przytulą się do Twojej szyi, cicho mrucząc i starając się ogrzać Cię swoim ciałem.\n\nUwielbiają spać pod kołdrą blisko serca opiekuna. Ze względu na swoją towarzyską naturę najlepiej czują się w domach, gdzie zawsze ktoś przebywa lub w towarzystwie drugiego kota, z którym mogą tworzyć zgraną, nierozłączną parę.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy'],
                'image_url' => 'https://images.unsplash.com/photo-1513245543132-31f507417b26?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'title' => 'Powrót do natury bez kompromisów: Holistyczne żywienie i dieta BARF jako recepta na długowieczność',
                'excerpt' => 'Kot to bezwzględny Mięsożerca (obligate carnivore). Sprawdź, jak żywienie oparte na surowym mięsie najwyższej klasy human-grade i braku zbóż odmienia zdrowie, sierść i witalność kota.',
                'body' => "Spojrzyj na anatolijską budowę anatomiczną swojego kota: krótkie jelito cienkie, ostro zakończone zęby przystosowane do chwytania i rozrywania tkanek, brak amylazy ślinowej do trawienia węglowodanów. Natura przez miliony lat ewolucji stworzyła perfekcyjną machinę – bezwzględnego mięsożercę (obligate carnivore).\n\nW hodowli z Mazowieckiej Szwajcarii nie ulegamy marketingowym modom. Nasze podejście do żywienia opiera się na biologicznej prawdzie i szacunku dla fizjologii kota.\n\n### Czym jest dieta BARF i żywienie holistyczne?\nBARF (Biologically Appropriate Raw Food) to sposób żywienia polegający na podawaniu surowego mięsa, podrobów, kości oraz niezbędnych suplementów (takich jak tauryna, kwasy tłuszczowe Omega-3 z dzikiego łososia, drożdże browarnicze czy mączka z alg morskich) precyzyjnie zbilansowanych pod kątem potrzeb konkretnego osobnika.\n\nAlternatywą lub uzupełnieniem w naszej hodowli są holistyczne, wysokomięsne karmy mokre klasy human-grade (min. 70-80% czystego mięsa mięśniowego), całkowicie pozbawione zbóż, soi, cukru, polepszaczy smaku czy sztucznych konserwantów.\n\n### Co zmienia prawidłowa dieta w życiu Twojego kota?\nEfekty prawidłowego żywienia biologicznie odpowiedniego są widoczne gołym okiem już po kilku tygodniach:\n\n- **1. Jedwabiste futro bez łupieżu:** Wysoki poziom czystej tauryny i kwasów tłuszczowych sprawia, że okrywa włosowa staje się gęsta, lśniąca i przestaje nadmiernie wypadac.\n- **2. Zwięzłe, bezwonne odchody i zdrowe układy wydalnicze:** W przeciwieństwie do karm zbożowych, surowe mięso jest przyswajalne w ponad 90%, co odciąża trzustkę, wątrobę i nerki.\n- **3. Czyste zęby i zdrowe przyzębie:** Gryzienie surowych kawałków mięsa naturalnie czyści kamień nazębny i masuje dziąsła.\n- **4. Niespożyta witalność i silne mięśnie:** Kot żywiony mięsem zachowuje smukłą sylwetkę bez tendencji do otyłości i cukrzycy.\n\n### Inwestycja w przyszłość\nKarmisz swojego kota zdrowo dzisiaj – oszczędzasz mu cierpienia, a sobie wizyt u weterynarza za 10 lat. Zapewnienie naszym kociętom od pierwszych tygodni życia najwyższej jakości pokarmu to nasz absolutny obowiązek jako odpowiedzialnych hodowców.",
                'is_published' => true,
                'category_names' => ['Żywienie Holistyczne', 'Zdrowie i Genetyka'],
                'image_url' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?q=80&w=1200&auto=format&fit=crop',
            ],
        ];

        foreach ($posts as $item) {
            $userId = $users[0]; // główny administrator / hodowca

            $post = Post::create([
                'user_id' => $userId,
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'excerpt' => $item['excerpt'],
                'body' => $item['body'],
                'is_published' => $item['is_published'],
                'published_at' => $item['is_published'] ? now()->subDays(rand(1, 14)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoryIds = Category::query()
                ->whereIn('name', $item['category_names'])
                ->pluck('id')
                ->all();

            $post->categories()->sync($categoryIds);

            if (!empty($item['image_url'])) {
                $post->media()->create([
                    'disk' => 'public',
                    'filename' => $item['image_url'],
                    'mime_type' => 'image/jpeg',
                    'size' => 102400,
                    'title' => $item['title'],
                    'alt_text' => $item['title'],
                    'is_featured' => true,
                ]);
            }
        }
    }
}
