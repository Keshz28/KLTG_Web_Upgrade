<?php
include('admin/functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" type="text/css" href="assets/css/lpage.css">
    <style>
        /* Add this CSS to your styles */
        select.form__input {
            max-height: 50px;
            /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="container">
            <div class="form-title">
                Welcome to
            </div>
            <div class="form_title">
                KL The Guide
            </div>
            <?php
            $email = $phone = $country = $state = $city = "";
            $emailErr = $phoneErr = $countryErr = $stateErr = $cityErr = "";
            $successMessage = $errorMessage = "";
            $submitted = false;

            function clean_data($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {


                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }


                if (empty($_POST["email"])) {
                    $emailErr = "Email is required";
                } else {
                    $email = clean_data($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }

                if (empty($_POST["phone"])) {
                    $phone = "";
                } else {
                    $phone = clean_data($_POST["phone"]);
                    if (ctype_alpha(preg_replace('/[0-9]+/', '', $phone))) {
                        $phoneErr = "Phone Number Cannot Include Letters";
                    } elseif (!ctype_digit(preg_replace('~[^0-9]~', '', $phone))) {
                        $phoneErr = "Your Phone Number Does Not Include Digits";
                    }
                }

                if (empty($_POST["country"])) {
                    $countryErr = "Country is required";
                } else {
                    $country = clean_data($_POST["country"]);
                }

                if (empty($_POST["state"])) {
                    $stateErr = "State is required";
                } else {
                    $state = clean_data($_POST["state"]);
                }

                if (isset($_POST["city"]) && !empty($_POST["city"])) {
                    $city = clean_data($_POST["city"]);
                } else {
                    $city = ''; // assign a default value if needed
                }

                if (empty($emailErr) && empty($phoneErr) && empty($countryErr) && empty($stateErr) && empty($cityErr)) {
                    $sql = "INSERT INTO contact_forms (email, phone, country, state, city) VALUES ('$email', '$phone', '$country', '$state', '$city')";

                    if ($db->query($sql) === TRUE) {
                        $submitted = true;
                        $successMessage = "Subscribe NOW and stand a chance to be the next MONTHLY WINNER!";
                        $link_address = "index.php";
                    } else {
                        $errorMessage = "Error: " . $sql . "<br>" . $db->error;
                    }
                }
            }

            if (!$submitted) {
            ?>

                <div class="form__container">

                    <div class="form_msg">
                        Please Fill in the Form before we redirect you to our Home Page
                    </div>


                    <form method="post" action="admin/sub_handler.php?action=subscribe">
                        <input type="hidden" name="consent" value="1">
                        <input type="hidden" name="source" value="qrpage">

                        <div class="form__row">
                            <label for="email" class="form__label">Email:</label>
                            <input class="form__input" type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="" autofill="email">
                            <span class="error"><?php echo $emailErr; ?></span>
                        </div>

                        <div class="form__row">
                            <label class="form__label" for="phone">Phone:</label>
                            <input class="form__input" type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="">
                            <span class="error"><?php echo $phoneErr; ?></span>
                        </div>

                        <div class="form__row">
                            <label class="form__label" for="country">Country:</label>
                            <select class="form__input" id="country" name="country">
                                <option value="" selected disabled></option>
                            </select>
                            <span class="error"><?php echo $countryErr; ?></span>
                        </div>

                        <div class="form__row">
                            <label class="form__label" for="state">State:</label>
                            <input class="form__input" type="text" id="state" name="state" value="<?php echo htmlspecialchars($state); ?>" placeholder="">
                            <span class="error"><?php echo $stateErr; ?></span>
                        </div>

                        <div class="form__row">
                            <label class="form__label" for="city">Location:</label>
                            <select class="form__input" id="city" name="city" placeholder="Select location qr scanned from">
                                <option value="" selected disabled>Select location qr scanned from</option>
                                <option value="Dewan_Bandaraya_Kuala_Lumpur">Dewan Bandaraya Kuala Lumpur (DBKL)</option>
                                <option value="Hentian_Duta">Hentian Duta</option>
                                <option value="KL_Sentral">KL Sentral</option>
                                <option value="Ruby_Lounge_KTMB_Intercity">Ruby Lounge KTMB Intercity</option>
                                <option value="Terminal_Putrajaya_Sentral_(TPS)">Terminal Putrajaya Sentral (TPS)</option>
                                <option value="Terminal_Bersepadu_Selatan_(TBS)">Terminal Bersepadu Selatan (TBS)</option>
                                <option value="Terminal_17_Shah_Alam">Terminal 17 Shah Alam</option>
                                <option value="Others">Others</option>
                            </select>
                            <span class="error"><?php echo $cityErr; ?></span>
                        </div>

                        <div>
                            <input class="submit" type="submit" name="submit_form" value="Submit" onclick="this.disabled=true; this.value='Submitting...'; this.form.submit();">
                        </div>

                    </form>
                </div>

        </div>

    </div>
<?php
            } else {
                echo "<p>$successMessage</p>";
                echo "<a href='" . $link_address . "'>Click Here to KL The Guide Homepage !!</a>";
            }
            if ($errorMessage) {
                echo "<p class='error'>$errorMessage</p>";
            }
?>

<script>
    var countryStateInfo = {
        Afghanistan: {},
        Albania: {},
        Algeria: {},
        Andorra: {},
        Angola: {},
        Anguilla: {},
        AntiguaandBarbuda: {},
        Argentina: {},
        Armenia: {},
        Aruba: {},
        Australia: {},
        Austria: {},
        Azerbaijan: {},
        Bahamas: {},
        Bahrain: {},
        Bangladesh: {},
        Barbados: {},
        Belarus: {},
        Belgium: {},
        Belize: {},
        Benin: {},
        Bermuda: {},
        Bhutan: {},
        Bolivia: {},
        BosniaandHerzegovina: {},
        Botswana: {},
        Brazil: {},
        BritishVirginIslands: {},
        Brunei: {},
        Bulgaria: {},
        BurkinaFaso: {},
        Burundi: {},
        CaboVerde: {},
        Cambodia: {},
        Cameroon: {},
        Canada: {},
        CaribbeanNetherlands: {},
        CaymanIslands: {},
        CentralAfricanRepublic: {},
        Chad: {},
        Chile: {},
        China: {},
        ChristmasIsland: {},
        CocosKeelingIslands: {},
        Colombia: {},
        Comoros: {},
        CongoDemocraticRepublic: {},
        CongoRepublic: {},
        CookIslands: {},
        CostaRica: {},
        Croatia: {},
        Cuba: {},
        Curacao: {},
        Cyprus: {},
        CzechRepublic: {},
        Denmark: {},
        Djibouti: {},
        Dominica: {},
        DominicanRepublic: {},
        Ecuador: {},
        Egypt: {},
        ElSalvador: {},
        EquatorialGuinea: {},
        Eritrea: {},
        Estonia: {},
        Eswatini: {},
        Ethiopia: {},
        FalklandIslands: {},
        FaroeIslands: {},
        Fiji: {},
        Finland: {},
        France: {},
        FrenchGuiana: {},
        FrenchPolynesia: {},
        Gabon: {},
        Gambia: {},
        Georgia: {},
        Germany: {},
        Ghana: {},
        Gibraltar: {},
        Greece: {},
        Greenland: {},
        Grenada: {},
        Guadeloupe: {},
        Guam: {},
        Guatemala: {},
        Guernsey: {},
        Guinea: {},
        GuineaBissau: {},
        Guyana: {},
        Haiti: {},
        Honduras: {},
        HongKong: {},
        Hungary: {},
        Iceland: {},
        India: {},
        Indonesia: {},
        Iran: {},
        Iraq: {},
        Ireland: {},
        IsleofMan: {},
        Israel: {},
        Italy: {},
        IvoryCoast: {},
        Jamaica: {},
        Japan: {},
        Jersey: {},
        Jordan: {},
        Kazakhstan: {},
        Kenya: {},
        Kiribati: {},
        Kosovo: {},
        Kuwait: {},
        Kyrgyzstan: {},
        Laos: {},
        Latvia: {},
        Lebanon: {},
        Lesotho: {},
        Liberia: {},
        Libya: {},
        Liechtenstein: {},
        Lithuania: {},
        Luxembourg: {},
        Macau: {},
        Madagascar: {},
        Malawi: {},
        Malaysia: {},
        Maldives: {},
        Mali: {},
        Malta: {},
        MarshallIslands: {},
        Martinique: {},
        Mauritania: {},
        Mauritius: {},
        Mayotte: {},
        Mexico: {},
        Micronesia: {},
        Moldova: {},
        Monaco: {},
        Mongolia: {},
        Montenegro: {},
        Montserrat: {},
        Morocco: {},
        Mozambique: {},
        Myanmar: {},
        Namibia: {},
        Nauru: {},
        Nepal: {},
        Netherlands: {},
        NewCaledonia: {},
        NewZealand: {},
        Nicaragua: {},
        Niger: {},
        Nigeria: {},
        Niue: {},
        NorfolkIsland: {},
        NorthKorea: {},
        NorthMacedonia: {},
        NorthernMarianaIslands: {},
        Norway: {},
        Oman: {},
        Pakistan: {},
        Palau: {},
        Palestine: {},
        Panama: {},
        PapuaNewGuinea: {},
        Paraguay: {},
        Peru: {},
        Philippines: {},
        PitcairnIslands: {},
        Poland: {},
        Portugal: {},
        PuertoRico: {},
        Qatar: {},
        Reunion: {},
        Romania: {},
        Russia: {},
        Rwanda: {},
        SaintBarthelemy: {},
        SaintHelena: {},
        SaintKittsandNevis: {},
        SaintLucia: {},
        SaintMartin: {},
        SaintPierreandMiquelon: {},
        SaintVincentandtheGrenadines: {},
        Samoa: {},
        SanMarino: {},
        SaoTomeandPrincipe: {},
        SaudiArabia: {},
        Senegal: {},
        Serbia: {},
        Seychelles: {},
        SierraLeone: {},
        Singapore: {},
        SintMaarten: {},
        Slovakia: {},
        Slovenia: {},
        SolomonIslands: {},
        Somalia: {},
        SouthAfrica: {},
        SouthGeorgiaandtheSouthSandwichIslands: {},
        SouthKorea: {},
        SouthSudan: {},
        Spain: {},
        SriLanka: {},
        Sudan: {},
        Suriname: {},
        Sweden: {},
        Switzerland: {},
        Syria: {},
        Taiwan: {},
        Tajikistan: {},
        Tanzania: {},
        Thailand: {},
        TimorLeste: {},
        Togo: {},
        Tokelau: {},
        Tonga: {},
        TrinidadandTobago: {},
        Tunisia: {},
        Turkey: {},
        Turkmenistan: {},
        TurksandCaicosIslands: {},
        Tuvalu: {},
        Uganda: {},
        Ukraine: {},
        UAE: {},
        UK: {},
        USA: {},
        Uruguay: {},
        Uzbekistan: {},
        Vanuatu: {},
        VaticanCity: {},
        Venezuela: {},
        Vietnam: {},
        VirginIslands: {},
        WallisandFutuna: {},
        WesternSahara: {},
        Yemen: {},
        Zambia: {},
        Zimbabwe: {}
    };


    window.onload = function() {
        const countrySelection = document.querySelector("#country");

        // Populate country dropdown
        for (let country in countryStateInfo) {
            countrySelection.options[countrySelection.options.length] = new Option(country, country);
        }

        countrySelection.onchange = function() {
            stateSelection.disabled = false;
            stateSelection.length = 1; // Clear state options

            let selectedCountry = countrySelection.value;
            stateSelection.innerHTML = ""; // Clear previous options

            if (selectedCountry !== "") {
                for (let state in countryStateInfo[selectedCountry]) {
                    stateSelection.options[stateSelection.options.length] = new Option(state, state);
                }
            }
        };


    };
</script>

</body>

</html>