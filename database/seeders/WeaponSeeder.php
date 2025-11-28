<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class WeaponSeeder extends Seeder
{
    public function run(): void
    {
        $arsenal = [
            'Assault Rifle' => [
                ['name' => 'L85A2', 'desc' => 'Standard issue assault rifle for S.A.S operators.'],
                ['name' => 'R4-C', 'desc' => 'FBI SWAT standard assault rifle, highly customizable.'],
                ['name' => 'G36C', 'desc' => 'Compact assault rifle with moderate recoil.'],
                ['name' => 'F2', 'desc' => 'FAMAS F1. High fire rate bullpup assault rifle.'],
                ['name' => 'AK-12', 'desc' => 'Modern Russian assault rifle with high damage.'],
                ['name' => '556xi', 'desc' => 'SIG SG 556. Balanced rifle for Thermite.'],
                ['name' => 'C7E', 'desc' => 'Jackal\'s assault rifle. High accuracy and damage.'],
                ['name' => 'M762', 'desc' => 'Zofia\'s assault rifle. Hard hitting 7.62mm rounds.'],
                ['name' => 'V308', 'desc' => 'Lion\'s vector-based rifle. Features a 50-round drum mag and very low recoil.'],
                ['name' => 'C8-SFW', 'desc' => 'Buck\'s assault rifle. High recoil, integrated Skeleton Key shotgun.'],
                ['name' => 'M4', 'desc' => 'Maverick\'s custom AR-15. Reliable and versatile.'],
                ['name' => 'Type-89', 'desc' => 'Hibana\'s assault rifle. High stopping power but small magazine capacity.'],
                ['name' => 'SC3000K', 'desc' => 'Zero\'s assault rifle. Modern bullpup with high fire rate.'],
                ['name' => 'PARA-308', 'desc' => 'Capitão\'s battle rifle. High damage, lower fire rate.'],
                ['name' => 'Spear .308', 'desc' => 'Bullpup rifle used by Finka and Thunderbird. Controllable recoil.'],
                ['name' => 'AR33', 'desc' => 'Thatcher and Flores\' alternative rifle. Burst fire capable.'],
                ['name' => '416-C Carbine', 'desc' => 'Jäger\'s carbine. Technically an AR, used by a defender.'],
                ['name' => 'Commando 552', 'desc' => 'IQ\'s assault rifle. High damage and range.'],
                ['name' => 'AK-74M', 'desc' => 'Tachanka\'s assault rifle. Reliable and powerful.'],
            ],

            'Submachine Gun' => [
                ['name' => 'MP5', 'desc' => 'GIGN standard SMG. Iconic and stable.'],
                ['name' => 'MP7', 'desc' => 'High fire rate SMG used by GSG 9.'],
                ['name' => 'UMP45', 'desc' => 'FBI SWAT SMG. High stopping power, slow fire rate.'],
                ['name' => 'P90', 'desc' => 'GIGN SMG with large magazine capacity.'],
                ['name' => 'Vector .45 ACP', 'desc' => 'Mira\'s SMG with extremely high fire rate.'],
                ['name' => 'T-5 SMG', 'desc' => 'Lesion\'s SMG. Compact and reliable.'],
                ['name' => '9x19VSN', 'desc' => 'Spetsnaz SMG. Balanced with moderate recoil.'],
                ['name' => 'AUG A2', 'desc' => 'IQ and Wamai\'s bullpup rifle/SMG hybrid.'],
                ['name' => 'Scorpion EVO 3 A1', 'desc' => 'Ela\'s SMG with high capacity and erratic recoil.'],
                ['name' => 'Mx4 Storm', 'desc' => 'Alibi\'s SMG. High fire rate and mobility.'],
                ['name' => 'Commando 9', 'desc' => 'Mozzie\'s AR converted to 9mm. Unique reload animation.'],
                ['name' => 'P10 RONI', 'desc' => 'Mozzie and Aruni\'s SMG. Low recoil, low ammo capacity.'],
                ['name' => 'MPX', 'desc' => 'Valkyrie and Warden\'s SMG. Low damage but laser accuracy.'],
                ['name' => 'FMG-9', 'desc' => 'Nokk and Smoke\'s folding SMG. High rate of fire.'],
                ['name' => 'UZK50GI', 'desc' => 'Thorn\'s custom SMG. High caliber, packs a punch.'],
            ],

            'Marksman Rifle' => [
                ['name' => '417', 'desc' => 'GIGN semi-automatic marksman rifle.'],
                ['name' => 'OTs-03', 'desc' => 'Glaz\'s unique sniper rifle with thermal scope capability.'],
                ['name' => 'CAMRS', 'desc' => 'JTF2 marksman rifle. High damage semi-auto.'],
                ['name' => 'Mk 14 EBR', 'desc' => 'Dokkaebi\'s battle rifle. Versatile range.'],
                ['name' => 'SR-25', 'desc' => 'Blackbeard and Flores\' DMR. High damage output.'],
                ['name' => 'AR-15.50', 'desc' => 'Maverick\'s DMR. Chambered in .50 Beowulf for destruction.'],
                ['name' => 'CSRX 300', 'desc' => 'Kali\'s bolt-action sniper. Can down enemies in one shot to the torso.'],
            ],

            'Shotgun' => [
                ['name' => 'M590A1', 'desc' => 'S.A.S pump action shotgun. Great for utility.'],
                ['name' => 'M1014', 'desc' => 'FBI SWAT semi-automatic shotgun.'],
                ['name' => 'SG-CQB', 'desc' => 'GIGN pump action shotgun. High close range damage.'],
                ['name' => 'Super 90', 'desc' => 'JTF2 semi-automatic shotgun.'],
                ['name' => 'SASG-12', 'desc' => 'Spetsnaz semi-automatic shotgun. High capacity and fire rate.'],
                ['name' => 'BOSG.12.2', 'desc' => '707th SMB over-under shotgun. Fires slugs with sniper-like range.'],
                ['name' => 'ACS12', 'desc' => 'Alibi and Maestro\'s full-auto slug shotgun. Destructive.'],
                ['name' => 'FO-12', 'desc' => 'Ela\'s semi-auto shotgun. Devastating in close quarters.'],
                ['name' => 'ITA12L', 'desc' => 'Jackal and Mira\'s pump shotgun. Good for soft destruction.'],
                ['name' => 'TCSG12', 'desc' => 'Kaid and Goyo\'s slug shotgun. Functions like a DMR.'],
                ['name' => 'M870', 'desc' => 'Thorn\'s pump action shotgun. Reliable and powerful.'],
                ['name' => 'SuperNova', 'desc' => 'Flores\' pump action shotgun. High damage and range.'],
                ['name' => 'SG-CQB', 'desc' => 'Nøkk\'s pump action shotgun. High close range damage.'],
            ],

            'Light Machine Gun' => [
                ['name' => '6P41', 'desc' => 'Fuze and Finka\'s belt-fed LMG. High damage with a 100-round box.'],
                ['name' => 'M249', 'desc' => 'Capitão\'s squad automatic weapon. High capacity belt-fed LMG.'],
                ['name' => 'ALDA 5.56', 'desc' => 'Maestro\'s LMG. High fire rate and capacity, unique to defense.'],
                ['name' => 'T-95 LSW', 'desc' => 'Ying\'s magazine-fed LMG. Fast reload for its class.'],
                ['name' => 'LMG-E', 'desc' => 'Zofia\'s belt-fed LMG. Massive 150-round capacity.'],
                ['name' => 'G8A1', 'desc' => 'IQ and Amaru\'s LMG. Plays like an assault rifle with a 50-round drum.'],
                ['name' => 'M60-E4', 'desc' => 'Flores\' LMG. High damage and controllable recoil.'],
            ],

            'Handgun' => [
                ['name' => 'P226 Mk 25', 'desc' => 'S.A.S standard sidearm.'],
                ['name' => '5.7 USG', 'desc' => 'FBI SWAT sidearm with large magazine.'],
                ['name' => 'LFP586', 'desc' => 'GIGN .357 Magnum revolver. High damage.'],
                ['name' => 'D-50', 'desc' => 'Navy SEALs Desert Eagle. Massive stopping power.'],
                ['name' => 'PMM', 'desc' => 'Spetsnaz pistol. Known for high damage and fast reload.'],
                ['name' => 'P9', 'desc' => 'JTF2 standard sidearm. Balanced performance.'],
                ['name' => 'C75 Auto', 'desc' => 'CZ 75 Auto. Blocky iron sights but reliable recoil.'],
                ['name' => 'Q-929', 'desc' => 'Ying\'s semi-automatic pistol. High magazine capacity.'],
                ['name' => 'RG15', 'desc' => 'Handgun with integrated red dot sight used by Ela and Zofia.'],
                ['name' => 'Keratos .357', 'desc' => 'High damage revolver with low recoil used by Italian operators.'],
                ['name' => 'Bailiff 410', 'desc' => 'Revolver shotgun sidearm. Excellent for making rotation holes.'],
                ['name' => 'P12', 'desc' => 'GSG 9 standard tactical pistol.'],
                ['name' => 'D-Devil .44', 'desc' => 'Thorn\'s high-caliber revolver. Devastating damage.'],
                ['name' => 'PRB92', 'desc' => 'Aruni\'s semi-automatic pistol. Balanced and reliable.'],
                ['name' => 'USP40', 'desc' => 'Warden\'s tactical pistol. Features night sights for ADS in smoke.'],
                ['name' => 'P320', 'desc' => 'Valkyrie\'s standard sidearm. High accuracy and low recoil.'],
                ['name' => 'M45 MEUSOC', 'desc' => 'Nokk\'s .45 ACP pistol. High damage and stopping power.'],
                ['name' => 'Q-21', 'desc' => 'Thorn\'s semi-automatic pistol. High capacity and low recoil.'],
                ['name' => '5.11 Tactical', 'desc' => 'Aruni\'s tactical pistol. Balanced performance.'],
                ['name' => 'P229', 'desc' => 'Flores\' semi-automatic pistol. Reliable and accurate.'],
                ['name' => 'D-12', 'desc' => 'Mozzie\'s semi-automatic pistol. High magazine capacity.'],
            ],

            'Machine Pistol' => [
                ['name' => 'SMG-11', 'desc' => 'S.A.S machine pistol. Known as the pocket sniper.'],
                ['name' => 'Bearing 9', 'desc' => 'SAT machine pistol. High recoil, high fire rate.'],
                ['name' => 'SMG-12', 'desc' => '707th SMB machine pistol. Integral suppressor and extreme recoil.'],
                ['name' => 'C75 Auto', 'desc' => 'CZ 75 Auto. Blocky iron sights but reliable recoil.'],
                ['name' => 'SPSMG9', 'desc' => 'Clash and Kali\'s machine pistol. Includes a reflex sight.'],
                ['name' => 'PMM', 'desc' => 'Spetsnaz pistol. Known for high damage and fast reload.'],
            ],

            'Sights' => [
                ['name' => 'Telescopic', 'desc' => 'Provides 3.5x magnification for long-range engagements.'],
                ['name' => 'Magnified', 'desc' => 'Provides 2.5x magnification. Standard ACOG class.'],
                ['name' => 'Non-magnifying', 'desc' => 'Red Dot, Holo, and Reflex sights. Increases ADS speed by 5%.'],
                ['name' => 'Iron sight', 'desc' => 'Default iron sights. Increases ADS speed by 10%.'],
            ],

            'Barrels' => [
                ['name' => 'Flash Hider', 'desc' => 'Reduces vertical recoil by 20%. Best for vertical kick control.'],
                ['name' => 'Compensator', 'desc' => 'Reduces horizontal recoil by 40%. Essential for weapons with side-to-side drift.'],
                ['name' => 'Muzzle Brake', 'desc' => 'Reduces first shot recoil by 50%. The go-to for DMRs and Pistols.'],
                ['name' => 'Suppressor', 'desc' => 'Removes bullet trails and threat indicators. No damage reduction.'],
                ['name' => 'Extended Barrel', 'desc' => 'Increases base damage by 10% and reduces damage drop-off ranges.'],
            ],

            'Grips' => [
                ['name' => 'Vertical Grip', 'desc' => 'Reduces vertical recoil by 20%. Standard choice for recoil control.'],
                ['name' => 'Angled Grip', 'desc' => 'Increases reload speed by 20%. aggressive playstyle choice.'],
                ['name' => 'Horizontal Grip', 'desc' => 'Increases operator movement speed by 5%. Great for roaming.'],
            ],

            'Under Barrel' => [
                ['name' => 'Laser', 'desc' => 'Increases ADS speed by 10%. Laser point is visible to enemies.'],
            ],

            'Gadgets' => [
                ['name' => 'Frag Grenade', 'desc' => 'High-explosive grenade. Can be cooked. effective for clearing utility and kills.'],
                ['name' => 'Stun Grenade', 'desc' => 'Flashbang. Blinds enemies facing the detonation. Used to burn ADS (Jäger/Wamai).'],
                ['name' => 'Smoke Grenade', 'desc' => 'Deploys a smoke cloud blocking line of sight. Essential for planting the defuser.'],
                ['name' => 'Claymore', 'desc' => 'Anti-personnel mine with laser tripwires. Protects flanks and runouts.'],
                ['name' => 'Hard Breach Charge', 'desc' => 'Deployable charge capable of destroying reinforced walls and hatches.'],
                ['name' => 'Impact EMP Grenade', 'desc' => 'Disables electronic devices within range upon impact. Alternative to Thatcher.'],
                ['name' => 'Barbed Wire', 'desc' => 'Slows down attackers and makes noise when moved through.'],
                ['name' => 'Deployable Shield', 'desc' => 'Provides portable cover. Slits allow defenders to see through.'],
                ['name' => 'Nitro Cell', 'desc' => 'Remote detonated C4 explosive. Massive damage, can be thrown.'],
                ['name' => 'Bulletproof Camera', 'desc' => 'Stationary camera immune to bullets from the front. Can fire EMP bursts.'],
                ['name' => 'Proximity Alarm', 'desc' => 'Small sticky device that emits a loud noise when attackers are near.'],
                ['name' => 'Impact Grenade', 'desc' => 'Explodes on contact. Used for creating rotation holes between sites.'],
                ['name' => 'Observation Blocker', 'desc' => 'Projects a digital screen blocking drone line of sight.'],
            ],
        ];
        foreach ($arsenal as $categoryName => $weapons) {
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'description' => "Weapons classified as {$categoryName} in Rainbow Six Siege.",
                    'image' => null
                ]
            );

            foreach ($weapons as $weapon) {

                Product::create([
                    'category_id'   => $category->id,
                    'sku'           => 'SKU-' . mt_rand(1000, 9999) . '-' . Str::lower(Str::random(4)),
                    'name'          => $weapon['name'],
                    'description'   => $weapon['desc'],
                    'buy_price'     => rand(1000, 5000) * 1000,
                    'sell_price'    => rand(6000, 10000) * 1000,
                    'stock'         => rand(5, 50),
                    'min_stock'     => 5,
                    'unit'          => 'Unit',
                    'rack_location' => 'Rak-' . chr(rand(65, 90)) . rand(1, 9), //Rak-A1
                    'image'         => null,
                ]);
            }
        }
    }
}
