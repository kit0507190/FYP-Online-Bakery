document.addEventListener('DOMContentLoaded', function () {

        // ---------------------------------------------------------
        // PRODUCTS: keep your original items (IDs 1-29) and add 30-38
        // (I preserved your original array entries and appended new ones)
        // ---------------------------------------------------------

        const products = [
            // 5 inch Cake (original entries you provided)
            {
                id: 1,
                name: "A LITTLE SWEET",
                price: 98.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/A_Little_Sweet.jpg",
                description: "Delicate 5 inch cake with a light and airy texture that melts in your mouth",
                fullDescription: "Our signature 'A LITTLE SWEET' cake is a perfect indulgence for any occasion. Featuring an incredibly light and airy texture that literally melts in your mouth, this 5 inch delight is crafted with the finest ingredients to bring you a moment of pure happiness.",
                ingredients: "Premium flour, fine sugar, fresh eggs, whole milk, creamy butter, pure vanilla extract",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.7,
                reviewCount: 42,
                tags: ["popular", "light", "signature"],
                size: "5 INCH"
            },
            {
                id: 2,
                name: "BABY PANDAA",
                price: 140.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Baby_Pandaa.jpg",
                description: "Premium Japanese matcha creates a beautiful green color and delicate flavor",
                fullDescription: "Our adorable BABY PANDAA cake combines premium Japanese matcha with charming panda design. The rich matcha flavor creates a beautiful green hue while maintaining a delicate balance of sweetness. Perfect for celebrations or as a special treat that's almost too cute to eat!",
                ingredients: "Flour, sugar, eggs, premium Japanese matcha powder, milk, butter",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains gluten, eggs, dairy",
                rating: 4.5,
                reviewCount: 28,
                tags: ["matcha", "japanese", "cute", "panda"],
                size: "5 INCH"
            },

            // Cheese Flavour
            
            {
    id: 73,
    name: "BLUEBERRY CHEESE",
    price: 88.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Blueberry_Cheese(cheese flavour).jpg",
    description: "Velvety blueberry cheesecake topped with glossy berry compote and fresh fruits.",
    fullDescription: "BLUEBERRY CHEESE features a smooth, creamy cheesecake layer with a sweet-tart blueberry compote crown. Finished with fresh strawberries and blueberries, this cake delivers a balanced creaminess and fruity brightness — perfect for classic cheesecake lovers.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, blueberry compote, graham cracker crust, fresh strawberries, blueberries",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 12,
    tags: ["cheese", "blueberry", "fresh"],
    size: "5 INCH"
},
{
    id: 74,
    name: "CHOCOLATE CHEESE",
    price: 138.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Chocolate_Cheese.jpg",
    description: "Rich chocolate cheesecake with cocoa-sprinkled top and fresh berry garnish.",
    fullDescription: "CHOCOLATE CHEESE combines creamy cheesecake with a rich chocolate layer and cocoa finish. Garnished with fresh strawberries and blueberries, it blends silky chocolate depth with classic cheesecake texture for a decadent treat.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, cocoa powder, dark chocolate, graham cracker crust, fresh strawberries, blueberries",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.8,
    reviewCount: 14,
    tags: ["cheese", "chocolate", "decadent"],
    size: "5 INCH"
},
{
    id: 75,
    name: "JAPANESE COTTON CHEESE",
    price: 148.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Japanese_Cheese.jpg",
    description: "Light and airy Japanese cotton-style cheesecake with delicate creaminess.",
    fullDescription: "JAPANESE COTTON CHEESE delivers a fluffy, soufflé-like texture with a gentle cheese flavor. Lower in richness but high in melt-in-your-mouth softness, it’s an elegant choice for those who prefer a light and cloud-like cheesecake.",
    ingredients: "Cream cheese, eggs, sugar, flour, milk, butter, cornstarch",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 11,
    tags: ["cheese", "japanese", "light"],
    size: "5 INCH"
},
{
    id: 76,
    name: "LEMON CHEESE CLASSIC",
    price: 78.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Lemon_Cheese.jpg",
    description: "Refreshing lemon cheesecake with zesty citrus glaze and buttery base.",
    fullDescription: "LEMON CHEESE CLASSIC balances tangy lemon curd with silky cream cheese filling and a crisp graham base. Topped with lemon slices and a glossy lemon glaze, it offers a bright, refreshing finish ideal for sunny celebrations.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, lemon juice, lemon zest, graham cracker crust",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 16,
    tags: ["cheese", "lemon", "zesty"],
    size: "5 INCH"
},
{
    id: 77,
    name: "OREO CHEESE DELIGHT",
    price: 138.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Oreo_Cheese.jpg",
    description: "Cookies-and-cream cheesecake layered with Oreo crumbs and creamy filling.",
    fullDescription: "OREO CHEESE DELIGHT layers classic cheesecake with generous Oreo cookie crumbs in both crust and filling. Finished with a dusting of crushed cookies and whole Oreo accents, it’s a playful and indulgent crowd-pleaser.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, Oreo cookies (crumbs and pieces), graham/crust base",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.8,
    reviewCount: 18,
    tags: ["cheese", "oreo", "cookies-and-cream"],
    size: "5 INCH"
},
{
    id: 78,
    name: "PEACH CHEESE BLOSSOM", 
    price: 98.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/peach cheese cake.jpeg",
    description: "Delicate peach-topped cheesecake with fruity glaze and buttery crust.",
    fullDescription: "PEACH CHEESE BLOSSOM features a creamy cheesecake base crowned with glossy poached peach pieces and a light fruity glaze. The balance of sweet peach and smooth cream cheese makes it a refreshing and elegant dessert option.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, poached peaches, peach glaze, graham cracker crust",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 13,
    tags: ["cheese", "peach", "fresh"],
    size: "5 INCH"
},
{
    id: 79,
    name: "RAINBOW LOVE CHEESE",
    price: 148.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Rainbow_Love_Cheese.jpg",
    description: "Vibrant rainbow-layered cheesecake topped with colorful macarons and sponge cubes.",
    fullDescription: "RAINBOW LOVE CHEESE is a joyful multi-layered cheesecake that pairs light, creamy cheese layers with colorful sponge inserts and a playful macaron topping. Bright, festive and perfect for celebrations or anyone who loves a cheerful, picture-ready dessert.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, sponge cake layers, macarons, graham cracker crust, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts (in macarons)",
    rating: 4.8,
    reviewCount: 20,
    tags: ["cheese", "rainbow", "macaron"],
    size: "5 INCH"
},
{
    id: 80,
    name: "RED VELVET CHEESE",
    price: 138.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Redvelvet-website.jpg",
    description: "Elegant red velvet cheesecake crowned with floral chocolate disc and rose accents.",
    fullDescription: "RED VELVET CHEESE combines the classic moist red velvet base with a silky cream-cheese layer, finished with a decorative white chocolate disc and delicate rose garnish. A refined twist on two beloved desserts in one elegant presentation.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, red velvet sponge, cocoa, white chocolate, edible rose decorations, graham cracker crust",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.7,
    reviewCount: 15,
    tags: ["cheese", "red velvet", "elegant"],
    size: "5 INCH"
},
{
    id: 81,
    name: "MANGO & BERRY CHEESE TART",
    price: 148.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Say_Cheese.jpg",
    description: "Deluxe mini cheesecake topped with fresh mango, berries and glossy fruit gel.",
    fullDescription: "MANGO & BERRY CHEESE TART features a smooth cream-cheese filling on a buttery base, topped with fresh mango cubes, strawberries, blueberries and a shiny fruit glaze. Bright, fruity and refreshing — a refined single-serve cheesecake experience in cake form.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, mango, strawberries, blueberries, fruit glaze, graham cracker crust",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 18,
    tags: ["cheese", "mango", "berry"],
    size: "5 INCH"
},
{
    id: 82,
    name: "TRIO CHOCO CHEESE",
    price: 158.00,
    category: "cake",
    subcategory: "cheese",
    image: "cake/Trio_Choco_Cheese.jpg",
    description: "Decadent three-layer chocolate & cheese cake finished with a dark chocolate glaze and macarons.",
    fullDescription: "TRIO CHOCO CHEESE layers dark chocolate mousse, creamy cheesecake and chocolate ganache on a crunchy base. Topped with macaron accents, chocolate decorations and a glossy finish, this cake is for chocolate lovers seeking complex textures and rich flavor.",
    ingredients: "Cream cheese, sugar, eggs, heavy cream, dark chocolate, milk chocolate, ganache, graham/crust base, macarons",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts (may contain)",
    rating: 4.9,
    reviewCount: 22,
    tags: ["cheese", "chocolate", "trio"],
    size: "5 INCH"
},


            // Chocolate & Coffee
{
    id: 83,
    name: "Belgium Chocolate",
    price: 95.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/BELGIUM CHOCOLATE.webp",
    description: "A rich Belgian chocolate cake layered with premium chocolate cream.",
    fullDescription: "Indulge in the intense flavour of our Belgium Chocolate cake, crafted with premium dark chocolate and layered with smooth chocolate cream. Perfect for true chocolate lovers.",
    ingredients: "Dark chocolate, flour, eggs, sugar, butter, cocoa powder",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 52,
    tags: ["chocolate", "premium", "belgium"],
    size: "8 INCH"
},
{
    id: 84,
    name: "Black Forest",
    price: 88.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/BLACK FOREST.webp",
    description: "Classic black forest cake with cherries and chocolate shavings.",
    fullDescription: "A timeless favourite—layers of light chocolate sponge, fresh cream, and cherries, topped with chocolate curls for the perfect finish.",
    ingredients: "Flour, cocoa, eggs, sugar, cream, cherries, chocolate",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 41,
    tags: ["classic", "cherry", "chocolate"],
    size: "8 INCH"
},
{
    id: 85,
    name: "Chocolate Mousse",
    price: 92.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/CHOCOLATE MOUSSE.webp",
    description: "Smooth and airy chocolate mousse cake with rich cocoa flavour.",
    fullDescription: "Our chocolate mousse cake is crafted with silky smooth chocolate mousse layered over soft sponge, delivering a melt-in-your-mouth experience.",
    ingredients: "Chocolate, cream, cocoa, gelatin, eggs, sugar",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 37,
    tags: ["mousse", "soft", "creamy"],
    size: "8 INCH"
},
{
    id: 86,
    name: "Chocolate Sesame",
    price: 89.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/CHOCOLATE SESAME.webp",
    description: "Unique blend of chocolate richness with nutty sesame aroma.",
    fullDescription: "A modern twist combining chocolate sponge with roasted sesame cream for a deep, aromatic flavour profile unlike traditional chocolate cakes.",
    ingredients: "Chocolate, sesame paste, flour, sugar, eggs, butter",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy, sesame",
    rating: 4.6,
    reviewCount: 28,
    tags: ["sesame", "unique", "aromatic"],
    size: "8 INCH"
},
{
    id: 87,
    name: "Chotiramisu",
    price: 98.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/CHOTIRAMISU.webp",
    description: "A chocolate-enhanced tiramisu layered with coffee cream.",
    fullDescription: "A delightful fusion of tiramisu and chocolate, blending rich cocoa layers with aromatic coffee-soaked sponge and mascarpone cream.",
    ingredients: "Mascarpone, coffee, cocoa, eggs, ladyfingers, sugar",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 44,
    tags: ["coffee", "tiramisu", "fusion"],
    size: "8 INCH"
},
{
    id: 88,
    name: "Green Gato",
    price: 90.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/GREEN GATO.webp",
    description: "A matcha-infused chocolate gateau with smooth creamy layers.",
    fullDescription: "Green Gato blends the earthy flavour of matcha with the richness of chocolate, creating a refined dessert with layered textures.",
    ingredients: "Matcha, chocolate, flour, eggs, sugar, cream",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 33,
    tags: ["matcha", "fusion", "premium"],
    size: "8 INCH"
},
{
    id: 89,
    name: "Moonlight Eve",
    price: 105.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/MOONLIGHT EVE.webp",
    description: "A luxury dark chocolate mousse cake with elegant design.",
    fullDescription: "Moonlight Eve features deep, velvety dark chocolate mousse layered with smooth cream and topped with an elegant chocolate sphere for a premium presentation.",
    ingredients: "Dark chocolate, cocoa, cream, sugar, eggs, butter",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 56,
    tags: ["luxury", "dark chocolate", "signature"],
    size: "8 INCH"
},
{
    id: 90,
    name: "Opera Cake",
    price: 110.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/OPERA.webp",
    description: "Classic French opera cake with coffee and chocolate layers.",
    fullDescription: "A sophisticated French dessert combining almond sponge soaked in coffee syrup, layered with chocolate ganache and coffee buttercream.",
    ingredients: "Almond flour, coffee, chocolate, butter, eggs, sugar",
    weight: "8-inch",
    allergens: "Contains gluten, nuts, eggs, dairy",
    rating: 4.9,
    reviewCount: 49,
    tags: ["french", "coffee", "premium"],
    size: "8 INCH"
},
{
    id: 91,
    name: "Rich Chocolate",
    price: 85.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/RICH CHOCOLATE.webp",
    description: "Deep, dense, and intensely chocolatey classic cake.",
    fullDescription: "A cake for true chocolate fans — dense cocoa sponge paired with rich chocolate buttercream for a bold flavour experience.",
    ingredients: "Cocoa, flour, eggs, sugar, butter",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 31,
    tags: ["classic", "dense", "chocolate"],
    size: "8 INCH"
},
{
    id: 92,
    name: "Tiramisu",
    price: 95.00,
    category: "cake",
    subcategory: "chocolate",
    image: "cake/Chocolate & Coffee/Tiramisu.webp",
    description: "Classic Italian tiramisu with rich mascarpone and espresso.",
    fullDescription: "A traditional tiramisu crafted with espresso-soaked ladyfingers, creamy mascarpone, and a generous dusting of cocoa for a perfect balance.",
    ingredients: "Mascarpone, coffee, cocoa, ladyfingers, eggs, sugar",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 45,
    tags: ["coffee", "italian", "classic"],
    size: "8 INCH"
},

    // Cute Mini Cake
{
    id: 114,
    name: "Mini Brown Bear For You Cake",
    price: 39.90,
    category: "cake",
    subcategory: "mini",
    image: "cake/Cute Mini Cake/CUTE MINI BROWN.webp",
    description: "Cute mini chocolate cream cake with a 3D brown bear holding a 'For You' sign.",
    fullDescription: "A 3-inch mini chocolate cake covered in smooth cocoa cream, topped with an adorable brown bear figure, fresh strawberry accents and crunchy chocolate pearls. Perfect for small celebrations, gifts or as a personal treat.",
    ingredients: "Flour, sugar, eggs, butter, cocoa powder, chocolate, fresh cream, strawberry, chocolate pearls",
    weight: "3-inch mini cake (serves 1–2)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 21,
    tags: ["mini", "cute", "bear"],
    size: "3 INCH"
},
{
    id: 116,
    name: "Mini Lazy Egg Drip Cake",
    price: 39.90,
    category: "cake",
    subcategory: "mini",
    image: "cake/Cute Mini Cake/CUTE MINI CAKE YELLOW.webp",
    description: "Yellow mini drip cake with a lazy egg character and pink hearts.",
    fullDescription: "A fun 3-inch mini cake frosted in pale yellow cream with rich chocolate drip, topped with a sleepy egg character, fresh strawberry and shimmering heart toppers. A playful design for birthdays and casual celebrations.",
    ingredients: "Flour, sugar, eggs, butter, chocolate, fresh cream, strawberry, fondant decorations",
    weight: "3-inch mini cake (serves 1–2)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.5,
    reviewCount: 16,
    tags: ["mini", "cute", "drip"],
    size: "3 INCH"
},
{
    id: 117,
    name: "Mini Oreo Brown Bear Cake",
    price: 39.90,
    category: "cake",
    subcategory: "mini",
    image: "cake/Cute Mini Cake/CUTE MINI DARK BISCUIT.webp",
    description: "Cookies & cream mini cake with a chocolate bear and Oreo pieces.",
    fullDescription: "A 3-inch cookies-and-cream style mini cake packed with crunchy biscuit crumbs, topped with a chocolate brown bear, Oreo chunks and chocolate cubes. Great for Oreo lovers who want a small, indulgent treat.",
    ingredients: "Flour, sugar, eggs, butter, chocolate, Oreo biscuits, fresh cream",
    weight: "3-inch mini cake (serves 1–2)",
    allergens: "Contains gluten, eggs, dairy, soy",
    rating: 4.6,
    reviewCount: 19,
    tags: ["mini", "oreo", "cute"],
    size: "3 INCH"
},
{
    id: 115,
    name: "Mini Purple Cat Strawberry Cake",
    price: 39.90,
    category: "cake",
    subcategory: "mini",
    image: "cake/Cute Mini Cake/CUTE MINI CAKE PURPLE.webp",
    description: "Pastel purple mini cake topped with a black cat and fresh strawberries.",
    fullDescription: "A 3-inch mini sponge cake coated in pastel purple cream, decorated with a charming black cat topper, whipped rosette border and juicy strawberries dusted with sugar. Ideal for cat lovers and small birthday surprises.",
    ingredients: "Flour, sugar, eggs, butter, cream, strawberries, fondant decorations",
    weight: "3-inch mini cake (serves 1–2)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 18,
    tags: ["animal", "cute", "cat"],
    size: "6 INCH"
},
{
    id: 118,
    name: "Mini Pink Bunny Cup Cake",
    price: 39.90,
    category: "cake",
    subcategory: "mini",
    image: "cake/Cute Mini Cake/CUTE MINI PINK.webp",
    description: "Pink mug-shaped mini cake with a bunny, lollipop and macarons.",
    fullDescription: "A creative 3-inch mini cake designed as a pink cup filled with whipped cream, a cute sleeping bunny, heart sprinkles and a lollipop topper, finished with two mini macarons at the base. Perfect for kids and kawaii-style celebrations.",
    ingredients: "Flour, sugar, eggs, butter, cream, macarons, fondant decorations",
    weight: "3-inch mini cake (serves 1–2)",
    allergens: "Contains gluten, eggs, dairy, nuts (in macarons)",
    rating: 4.7,
    reviewCount: 22,
    tags: ["mini", "cute", "bunny"],
    size: "3 INCH"
},

            
            // Durian Series
{
    id: 111,
    name: "Durian Father's Day Cake",
    price: 220.00,
    category: "cake",
    subcategory: "durian",
    image: "cake/Durian Series/Durian Cake 6 Inch.webp",
    description: "A Father's Day themed durian cake topped with realistic durian flesh decoration.",
    fullDescription: "A premium durian celebration cake layered with rich durian cream and decorated with a festive Father's Day topper along with realistic durian pulp styling. Perfect for gifting durian lovers.",
    ingredients: "Fresh durian, flour, sugar, eggs, butter, cream",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.7,
    reviewCount: 31,
    tags: ["durian", "father's day", "premium"],
    size: "6 INCH"
},
{
    id: 112,
    name: "Realistic Spiky Durian Cake",
    price: 190.00,
    category: "cake",
    subcategory: "durian",
    image: "cake/Durian Series/Durian Cake.webp",
    description: "A hyper-realistic durian-shaped cake with detailed spiky texture.",
    fullDescription: "Crafted to resemble an actual durian, this cake features intricate spiky icing, soft sponge layers, and rich durian cream filling. Ideal for durian enthusiasts who love creative cake designs.",
    ingredients: "Fresh durian, flour, eggs, sugar, cream, butter",
    weight: "8-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 22,
    tags: ["durian", "realistic", "creative"],
    size: "8 INCH"
},
{
    id: 113,
    name: "Durian Lover Mini Cake",
    price: 138.00,
    category: "cake",
    subcategory: "durian",
    image: "cake/Durian Series/DURIAN LOVER.webp",
    description: "A mini durian-themed cake topped with spiky durian decoration.",
    fullDescription: "A delightful mini-sized durian cake layered with creamy durian filling, topped with a signature spiky durian replica. Sweet, aromatic and perfect for durian lovers who enjoy rich flavors in smaller portions.",
    ingredients: "Fresh durian, flour, eggs, sugar, cream, butter, almonds",
    weight: "5-inch",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.6,
    reviewCount: 18,
    tags: ["durian", "mini", "premium"],
    size: "5 INCH"
},


            // Festival
            {
    id: 174,
    name: "Christmas Cottage Festival Cake",
    price: 268.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/Christmas Cottage Cake 6 Inch.webp",
    description: "Festive Christmas cottage cake decorated with Santa, Christmas tree and toy train.",
    fullDescription: "A beautifully handcrafted Christmas-themed cake designed with a chocolate tree-stump base, a gingerbread-style cottage, Santa Claus figurine, Christmas tree and an adorable red toy train surrounding the base. Finished with festive wreath details and snowy textures, this cake is perfect for family gatherings and Christmas celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, cocoa, chocolate, fondant decorations",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 36,
    tags: ["festival", "christmas", "cottage", "holiday"],
    size: "6 INCH"
},
{
    id: 175,
    name: "Floral Memorial Chrysanthemum Cake",
    price: 198.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/FLORAL MEMORIAL.webp",
    description: "Elegant floral memorial cake topped with white and yellow chrysanthemum flowers.",
    fullDescription: "A soft pastel fresh cream cake decorated with a full ring of realistic yellow and white chrysanthemum flowers, symbolising remembrance and respect. Designed for memorial services and tribute ceremonies, this cake delivers a gentle appearance with light, soothing flavours.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, vanilla",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.6,
    reviewCount: 22,
    tags: ["festival", "memorial", "floral", "chrysanthemum"],
    size: "6 INCH"
},
{
    id: 176,
    name: "Gold Ingot Prosperity Cake",
    price: 238.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/GOLD INGOT.webp",
    description: "Golden ingot-shaped cake decorated with chocolate coins and prosperity symbol.",
    fullDescription: "A luxurious gold ingot–shaped cake finished with a smooth metallic gold effect and surrounded by edible gold coins. Designed especially for Chinese New Year, business openings and prosperity celebrations, symbolising wealth, success and abundance.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, edible gold colouring, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 31,
    tags: ["festival", "cny", "prosperity", "wealth"],
    size: "6 INCH"
},
{
    id: 177,
    name: "Halloween Spooky Spider Cake",
    price: 198.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/HALLOWEEN 2024.webp",
    description: "Bright orange Halloween cake decorated with spooky spiders and chocolate drips.",
    fullDescription: "A vibrant Halloween cake in bright orange tones featuring creepy chocolate spider hangings, spooky decorations and rich chocolate drip details. Perfect for Halloween parties where fun and fright meet in one delicious dessert.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, cocoa, chocolate, fondant decorations",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.7,
    reviewCount: 27,
    tags: ["festival", "halloween", "spooky", "party"],
    size: "6 INCH"
},
{
    id: 178,
    name: "Heart of Love Rose Cake",
    price: 258.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/HEART OF LOVE.webp",
    description: "Romantic heart-shaped cake fully covered with red buttercream roses.",
    fullDescription: "A stunning heart-shaped cake decorated with hand-piped red buttercream roses and finished with a golden ‘Love’ script. Ideal for Valentine’s Day, proposals and anniversaries, this cake offers both visual elegance and rich flavour.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, vanilla, food colouring",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 43,
    tags: ["festival", "valentine", "heart", "romance"],
    size: "6 INCH"
},
{
    id: 179,
    name: "Horror Happy Hour Pumpkin Bunny Cake",
    price: 248.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/HORROR HAPPY HOUR.webp",
    description: "Pumpkin-style Halloween cake topped with playful bunny monster character.",
    fullDescription: "A playful Halloween cake designed with a bright pumpkin base and a mischievous bunny monster character wearing a spooky costume. Decorated with bat silhouettes and bold colours, it’s perfect for themed birthday parties and festive horror nights.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, cocoa, fondant decorations, food colouring",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 32,
    tags: ["festival", "halloween", "pumpkin", "monster"],
    size: "6 INCH"
},
{
    id: 180,
    name: "King's World Currency Prosperity Cake",
    price: 288.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/KING'S.webp",
    description: "Triple-tone money-themed cake decorated with global currency symbols.",
    fullDescription: "A premium prosperity cake featuring bold currency symbols such as ¥, $, €, and £ surrounding a luxurious golden money pouch filled with edible coins. Designed for grand openings, business achievements and milestone celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant, edible gold colouring",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.7,
    reviewCount: 25,
    tags: ["festival", "prosperity", "business", "currency"],
    size: "6 INCH"
},
{
    id: 181,
    name: "Kopi Gao Gao Father's Day Mug Cake",
    price: 268.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/KOPI GAO GAO.webp",
    description: "Classic kopi mug cake with biscuits and Father's Day topper.",
    fullDescription: "A nostalgic kopi-style mug cake printed with vintage floral patterns, topped with coffee cream foam and classic biscuits. Finished with a ‘Love You Dad’ topper, this cake is specially crafted for Father’s Day and coffee lovers.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, coffee, chocolate, biscuits",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 39,
    tags: ["festival", "father's day", "coffee", "kopi"],
    size: "6 INCH"
},
{
    id: 182,
    name: "Mack Daddy Black Gold Whiskey Cake",
    price: 298.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/MACK DADDY.webp",
    description: "Bold black and gold drip cake topped with whiskey bottle decorations.",
    fullDescription: "A bold luxury cake finished in matte black with gold leaf splatters, crowned with mini whiskey bottles, cream puffs and chocolate macarons. Designed for gentlemen celebrations, Father’s Day and milestone birthdays.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, cocoa, chocolate, macarons, fondant decorations",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.9,
    reviewCount: 35,
    tags: ["festival", "father's day", "whiskey", "luxury"],
    size: "6 INCH"
},
{
    id: 183,
    name: "Money Huatt Mahjong Treasure Cake",
    price: 288.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/MONEY HUATT.webp",
    description: "Prosperity chocolate drip cake decorated with gold mahjong tiles and coins.",
    fullDescription: "A rich chocolate drip cake decorated with golden mahjong tiles, chocolate pretzels and a golden prosperity tree topper. Designed especially for Chinese New Year, jackpot celebrations and business launches to bring full ‘Huatt’ energy.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, cocoa, chocolate, pretzels, fondant decorations",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.8,
    reviewCount: 29,
    tags: ["festival", "cny", "mahjong", "prosperity"],
    size: "6 INCH"
},
{
    id: 184,
    name: "I Love Daddy Nutty Festival Cake",
    price: 178.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/MR MOUSTACHE.webp",
    description: "Elegant purple-toned festival cake with nuts, blueberries and an 'I ❤️ Daddy' topper.",
    fullDescription: "A stylish Father’s Day festival cake frosted in a soft purple shade and decorated with crunchy walnuts, almonds, blueberries and fresh greenery. Finished with a playful moustache and gold 'I ❤️ Daddy' topper, it’s perfect for celebrating the most important man in the family.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, walnuts, almonds, blueberries",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.8,
    reviewCount: 32,
    tags: ["festival", "father's day", "nuts", "blueberry"],
    size: "6 INCH"
},
{
    id: 185,
    name: "3D Red Velvet Christmas Tree Cake",
    price: 258.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/Red Velvet 3D Christmas Tree Cake.jpg",
    description: "Tall 3D red velvet cake sculpted into a festive Christmas tree.",
    fullDescription: "A spectacular 3D Christmas tree cake built with moist red velvet sponge and creamy frosting, then piped all over with lush green rosettes and colourful sprinkles to resemble a real Christmas tree. A stunning centrepiece for any Christmas gathering.",
    ingredients: "Flour, eggs, sugar, butter, cocoa powder, cream cheese, fresh cream, food colouring",
    weight: "7-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 41,
    tags: ["christmas", "red velvet", "festival", "showpiece"],
    size: "7 INCH"
},
{
    id: 186,
    name: "Snowy Christmas Tree Buttercream Cake",
    price: 188.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/Simple Christmas Cake Ideas.jpg",
    description: "Snowy white buttercream cake with a piped Christmas tree design.",
    fullDescription: "A cosy Christmas cake covered in smooth white buttercream, decorated with delicate flower piping and a charming green Christmas tree on the side, finished with tiny golden and red accents. Simple yet festive, perfect for intimate year-end celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, vanilla extract, food colouring",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.7,
    reviewCount: 27,
    tags: ["christmas", "buttercream", "festival"],
    size: "6 INCH"
},
{
    id: 187,
    name: "Welcome Mr Gold Prosperity Cake",
    price: 238.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/WELCOME MR GOLD.webp",
    description: "Bright red prosperity cake topped with a God of Wealth figurine.",
    fullDescription: "A joyful Chinese New Year cake in auspicious red, crowned with a smiling God of Wealth figurine, gold coins and a glittering prosperity tree. Designed to bring good fortune and blessings, it’s ideal for family reunions and business gifting.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant decorations",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 53,
    tags: ["festival", "cny", "prosperity", "gold"],
    size: "6 INCH"
},
{
    id: 188,
    name: "Wonderful Year Fortune Cake",
    price: 228.00,
    category: "cake",
    subcategory: "festival",
    image: "cake/Festival/WONDERFUL YEAR.webp",
    description: "Red fortune cake decorated with coins, gold sprinkles and festive wording.",
    fullDescription: "A vibrant red celebration cake symbolising wealth and good luck, decorated with gold coins, sparkling sprinkles and auspicious Chinese characters. Perfect for New Year countdowns, opening ceremonies and any celebration wishing for a wonderful year ahead.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant, edible gold sprinkles",
    weight: "6-inch",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 38,
    tags: ["festival", "fortune", "cny", "celebration"],
    size: "6 INCH"
},


            // Fondant Cake Design
            {
    id: 131,
    name: "Pink Elegant Floral Fondant Cake",
    price: 320.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD056.webp",
    description: "Elegant pink floral fondant cake",
    fullDescription: "Beautiful handcrafted pink floral fondant cake perfect for elegant birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant, food coloring",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 46,
    tags: ["fondant", "floral", "elegant"],
    size: "6 INCH"
},
{
    id: 132,
    name: "Dinosaur Birthday Fondant Cake",
    price: 280.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD057.webp",
    description: "Cute dinosaur themed fondant cake",
    fullDescription: "Adorable dinosaur themed fondant cake specially designed for kids birthday parties.",
    ingredients: "Flour, eggs, sugar, butter, fondant, chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 39,
    tags: ["fondant", "kids", "dinosaur"],
    size: "6 INCH"
},
{
    id: 133,
    name: "Luxury Car Birthday Fondant Cake",
    price: 360.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD067.webp",
    description: "Luxury car design fondant cake",
    fullDescription: "Premium handmade fondant cake featuring luxury car theme for special birthdays.",
    ingredients: "Flour, eggs, sugar, butter, fondant, chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 51,
    tags: ["fondant", "luxury", "car"],
    size: "6 INCH"
},
{
    id: 134,
    name: "Unicorn Rainbow Fondant Cake",
    price: 340.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD069.webp",
    description: "Rainbow unicorn themed fondant cake",
    fullDescription: "Magical rainbow unicorn fondant cake perfect for dreamy birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant, food coloring",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 57,
    tags: ["fondant", "unicorn", "rainbow"],
    size: "6 INCH"
},
{
    id: 135,
    name: "Sweet Bunny Night Fondant Cake",
    price: 300.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD075.webp",
    description: "Cute bunny night theme fondant cake",
    fullDescription: "Adorable bunny themed fondant cake with moon and star design for sweet celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 33,
    tags: ["fondant", "bunny", "cute"],
    size: "6 INCH"
},
{
    id: 136,
    name: "Birthday Bear Fondant Cake",
    price: 280.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD078.webp",
    description: "Cute bear themed birthday fondant cake",
    fullDescription: "Lovely bear fondant cake perfect for kids and couple birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 41,
    tags: ["fondant", "bear", "cute"],
    size: "6 INCH"
},
{
    id: 137,
    name: "Plants vs Zombie Fondant Cake",
    price: 350.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD081.webp",
    description: "Plants vs Zombie theme fondant cake",
    fullDescription: "Fun and creative Plants vs Zombie themed fondant cake for game lovers.",
    ingredients: "Flour, eggs, sugar, butter, fondant, chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 49,
    tags: ["fondant", "game", "fun"],
    size: "6 INCH"
},
{
    id: 138,
    name: "Baby Airplane Fondant Cake",
    price: 330.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD084.webp",
    description: "Cute baby airplane theme fondant cake",
    fullDescription: "Dreamy baby airplane fondant cake perfect for baby birthdays and celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 52,
    tags: ["fondant", "baby", "airplane"],
    size: "6 INCH"
},
{
    id: 139,
    name: "Animal Friends Fondant Cake",
    price: 300.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD086.webp",
    description: "Cute animal friends fondant cake",
    fullDescription: "Lovely animal themed fondant cake perfect for children birthday parties.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 36,
    tags: ["fondant", "animal", "kids"],
    size: "6 INCH"
},
{
    id: 140,
    name: "Cute Baby One Year Fondant Cake",
    price: 360.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD087.webp",
    description: "Cute baby first birthday fondant cake",
    fullDescription: "Premium baby first birthday fondant cake with cute cartoon design.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 61,
    tags: ["fondant", "baby", "1st birthday"],
    size: "6 INCH"
},
{
  id: 141,
  name: "International Travel Airplane Fondant Cake",
  price: 360.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD088.webp",
  description: "International travel airplane themed fondant cake",
  fullDescription: "Premium international travel fondant cake with airplane and country flag decorations.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 58,
  tags: ["fondant", "airplane", "travel"],
  size: "6 INCH"
},
{
  id: 142,
  name: "Cute Bear Girl Fondant Cake",
  price: 320.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD089.webp",
  description: "Cute bear girl theme fondant cake",
  fullDescription: "Premium cute bear girl fondant cake with pastel colors and adorable decorations.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.8,
  reviewCount: 62,
  tags: ["fondant", "bear", "cute"],
  size: "6 INCH"
},
{
  id: 143,
  name: "Kungfu Panda Bamboo Fondant Cake",
  price: 320.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD090.webp",
  description: "Kungfu panda bamboo theme fondant cake",
  fullDescription: "Premium kungfu panda inspired fondant cake with bamboo forest design.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 71,
  tags: ["fondant", "panda", "cartoon"],
  size: "6 INCH"
},
{
  id: 144,
  name: "Captain America Superhero Fondant Cake",
  price: 360.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD091.webp",
  description: "Captain America superhero themed fondant cake",
  fullDescription: "Premium Captain America superhero fondant cake with shield design and bold colors.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 85,
  tags: ["fondant", "superhero", "captain america"],
  size: "6 INCH"
},
{
  id: 145,
  name: "Luxury Bow Brand Style Fondant Cake",
  price: 330.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD092.webp",
  description: "Luxury brand bow design fondant cake",
  fullDescription: "Premium luxury style fondant cake with elegant bow and branded pattern design.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.8,
  reviewCount: 67,
  tags: ["fondant", "luxury", "bow"],
  size: "6 INCH"
},
{
  id: 146,
  name: "Baby Elephant Forest Fondant Cake",
  price: 320.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD093.webp",
  description: "Baby elephant forest themed fondant cake",
  fullDescription: "Premium baby elephant fondant cake with forest leaves and cute animal decorations.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 73,
  tags: ["fondant", "elephant", "baby"],
  size: "6 INCH"
},
{
  id: 147,
  name: "Demon Slayer Anime Fondant Cake",
  price: 360.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD094.webp",
  description: "Demon Slayer anime themed fondant cake",
  fullDescription: "Premium Demon Slayer inspired fondant cake with detailed character face split design.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 5.0,
  reviewCount: 94,
  tags: ["fondant", "anime", "demon slayer"],
  size: "6 INCH"
},
{
  id: 148,
  name: "Hot Air Balloon Baby Girl Fondant Cake",
  price: 330.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD095.webp",
  description: "Hot air balloon baby girl theme fondant cake",
  fullDescription: "Premium baby girl fondant cake with hot air balloon and pastel cloud decorations.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 70,
  tags: ["fondant", "baby girl", "balloon"],
  size: "6 INCH"
},
{
  id: 149,
  name: "Thomas Train Birthday Fondant Cake",
  price: 340.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD096.webp",
  description: "Thomas train themed birthday fondant cake",
  fullDescription: "Premium Thomas train fondant cake with railway track and colorful balloon decorations.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 88,
  tags: ["fondant", "train", "kids"],
  size: "6 INCH"
},
{
  id: 150,
  name: "Firefighter Truck Birthday Fondant Cake",
  price: 340.00,
  category: "cake",
  subcategory: "fondant",
  image: "cake/Fondant Cake Design/FD097.webp",
  description: "Firefighter truck theme birthday fondant cake",
  fullDescription: "Premium firefighter fondant cake with fire truck, water splash, and rescue theme design.",
  ingredients: "Flour, eggs, sugar, butter, fondant",
  weight: "6-inch",
  allergens: "Contains gluten, eggs, dairy",
  rating: 4.9,
  reviewCount: 79,
  tags: ["fondant", "firefighter", "truck"],
  size: "6 INCH"
},
{
    id: 151,
    name: "Frozen Elsa Princess Fondant Cake",
    price: 320.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD098.webp",
    description: "Beautiful Frozen Elsa themed fondant cake with elegant blue princess design.",
    fullDescription: "This Frozen Elsa Princess fondant cake features a stunning icy blue gown design with detailed snowflake decorations, perfect for girls’ birthday celebrations and Frozen-themed parties.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 58,
    tags: ["fondant", "princess", "frozen"],
    size: "6 INCH"
},
{
    id: 152,
    name: "Roblox Theme Birthday Fondant Cake",
    price: 300.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD099.webp",
    description: "Fun and colorful Roblox themed fondant cake with playful block-style characters.",
    fullDescription: "This Roblox themed fondant cake is designed with vibrant colors and iconic block characters, making it a perfect choice for kids who love gaming and creative birthday parties.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 52,
    tags: ["fondant", "game", "roblox"],
    size: "6 INCH"
},
{
    id: 153,
    name: "Harry Potter Hedwig Fondant Cake",
    price: 360.00,
    category: "cake",
    subcategory: "fondant",
    image: "cake/Fondant Cake Design/FD100.webp",
    description: "Elegant Harry Potter themed fondant cake featuring the iconic Hedwig owl design.",
    fullDescription: "This premium Harry Potter Hedwig fondant cake is crafted with magical details, featuring books, wand elements and the beloved owl, perfect for Potter fans and themed celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fondant",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 67,
    tags: ["fondant", "harry potter", "movie"],
    size: "6 INCH"
},
// Fresh Cream Cake Series (id: 154–163)

{
    id: 154,
    name: "Snoopy Strawberry Fresh Cream Cake",
    price: 150.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK146.webp",
    description: "Round fresh cream cake with Snoopy illustration and strawberry base.",
    fullDescription: "A classic round fresh cream cake featuring a hand-drawn Snoopy hugging a heart, finished with fresh strawberries around the base for a light and cheerful celebration.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, strawberries",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 52,
    tags: ["fresh cream", "Snoopy", "strawberry", "cartoon"],
    size: "6 INCH"
},
{
    id: 155,
    name: "Galaxy Astronaut Fresh Cream Cake",
    price: 280.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK158.webp",
    description: "Galaxy-themed fresh cream cake topped with an astronaut and planets.",
    fullDescription: "Deep space inspired fresh cream cake with gradient galaxy glazing, detailed planets and a sitting astronaut topper, perfect for space lovers and birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, food colouring",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 64,
    tags: ["fresh cream", "galaxy", "astronaut", "space"],
    size: "6 INCH"
},
{
    id: 156,
    name: "Angella Bunny Music Fresh Cream Cake",
    price: 220.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK165.webp",
    description: "Pastel bunny fresh cream cake with guitar and cloud decorations.",
    fullDescription: "Soft pastel fresh cream cake featuring a cute bunny playing guitar, surrounded by smiling clouds and star toppers, ideal for music-themed or kids’ birthdays.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 57,
    tags: ["fresh cream", "bunny", "music", "kids"],
    size: "6 INCH"
},
{
    id: 157,
    name: "Doraemon Sphere Fresh Cream Cake",
    price: 260.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK166.webp",
    description: "3D Doraemon-shaped fresh cream cake with hammer accessory.",
    fullDescription: "A full 3D spherical Doraemon fresh cream cake with detailed facial features, bell and mini hammer, bringing a playful touch to anime-themed celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 71,
    tags: ["fresh cream", "Doraemon", "3D", "cartoon"],
    size: "6 INCH"
},
{
    id: 158,
    name: "Bear Friends Celebration Fresh Cream Cake",
    price: 240.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK169.webp",
    description: "Round fresh cream cake with giant bear and panda friends topper.",
    fullDescription: "Adorable party-themed fresh cream cake showcasing a big white bear in a party hat, paired with panda and brown bear figures, perfect for cute character lovers.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 63,
    tags: ["fresh cream", "bear", "panda", "cute"],
    size: "6 INCH"
},
{
    id: 159,
    name: "Giant Ferrero Rocher Fresh Cream Cake",
    price: 310.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK175.webp",
    description: "Oversized Ferrero Rocher-inspired chocolate fresh cream cake.",
    fullDescription: "Luxurious giant Ferrero Rocher style fresh cream cake with crunchy chocolate shell effect, surrounded by mini chocolates and lights for an impressive centerpiece.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, hazelnut paste",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy, nuts",
    rating: 4.9,
    reviewCount: 78,
    tags: ["fresh cream", "Ferrero Rocher", "chocolate", "luxury"],
    size: "6 INCH"
},
{
    id: 160,
    name: "Blue Planet Sphere Fresh Cream Cake",
    price: 290.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK176.webp",
    description: "Blue planet sphere fresh cream cake with golden orbits.",
    fullDescription: "Modern spherical planet-themed fresh cream cake decorated with marbled blue glazing, gold orbit balls and a ‘Happy Birthday’ topper, ideal for stylish galaxy parties.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, food colouring",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 54,
    tags: ["fresh cream", "planet", "sphere", "galaxy"],
    size: "6 INCH"
},
{
    id: 161,
    name: "Among Us Galaxy Fresh Cream Cake",
    price: 300.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK179.webp",
    description: "Galaxy planet fresh cream cake with Among Us characters.",
    fullDescription: "Game-inspired fresh cream cake with a colourful galaxy planet center and multiple Among Us style figures, finished with star details for a fun birthday surprise.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 69,
    tags: ["fresh cream", "Among Us", "gaming", "galaxy"],
    size: "6 INCH"
},
{
    id: 162,
    name: "Yellow Bear Smash Fresh Cream Cake",
    price: 180.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK181.webp",
    description: "Cute yellow bear smash-style fresh cream cake with hammer.",
    fullDescription: "Playful yellow bear themed fresh cream cake with rounded shape and included wooden hammer, designed for interactive smash-style birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 48,
    tags: ["fresh cream", "bear", "smash cake", "cute"],
    size: "6 INCH"
},
{
    id: 163,
    name: "Purple Bunny Smash Fresh Cream Cake",
    price: 200.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK182.webp",
    description: "Purple bunny smash-style fresh cream cake with bow and gift.",
    fullDescription: "Charming purple bunny fresh cream smash cake featuring a big polka-dot bow, matching hammer and mini gift box, perfect for kids who love interactive cakes.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 55,
    tags: ["fresh cream", "bunny", "smash cake", "kids"],
    size: "6 INCH"
},
{
    id: 164,
    name: "Sailor Moon Smash Fresh Cream Cake",
    price: 208.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK183.webp",
    description: "Sailor Moon themed smash fresh cream cake with magical wand and princess bow.",
    fullDescription: "Beautiful Sailor Moon inspired smash fresh cream cake designed with pastel pink dome shape, golden stars, princess bow ribbon and signature magical wand hammer for interactive birthday celebration.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 52,
    tags: ["fresh cream", "sailor moon", "smash cake", "anime"],
    size: "6 INCH"
},
{
    id: 165,
    name: "Unicorn Rose Smash Fresh Cream Cake",
    price: 215.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK184.webp",
    description: "Pastel unicorn smash fresh cream cake with floral decorations and golden horn.",
    fullDescription: "Adorable unicorn smash fresh cream cake featuring round pastel body, colorful rainbow mane, handcrafted roses, and golden unicorn horn for a dreamy birthday surprise.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant details",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 60,
    tags: ["fresh cream", "unicorn", "smash cake", "kids"],
    size: "6 INCH"
},
{
    id: 166,
    name: "Earth Globe Smash Fresh Cream Cake",
    price: 225.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK185.webp",
    description: "Planet-themed smash fresh cream cake with globe design and cloud decorations.",
    fullDescription: "Creative globe smash fresh cream cake designed with realistic Earth texture, clouds, hot air balloon and star topper, ideal for travel lovers and creative birthdays.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, colored cream",
    weight: "7-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 58,
    tags: ["fresh cream", "globe", "travel cake", "smash cake"],
    size: "7 INCH"
},
{
    id: 167,
    name: "Butterfly Princess Smash Fresh Cream Cake",
    price: 400.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK186.webp",
    description: "Elegant butterfly smash fresh cream cake with three-tier princess design.",
    fullDescription: "Luxury butterfly themed smash fresh cream cake designed with floating butterflies, crown topper, pearl piping, and layered pastel pink tiers for elegant birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate, fondant accents",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 5.0,
    reviewCount: 70,
    tags: ["fresh cream", "butterfly", "princess cake", "smash cake"],
    size: "8 INCH"
},
{
    id: 168,
    name: "Purple Chocolate Drip Smash Fresh Cream Cake",
    price: 218.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK187.webp",
    description: "Purple gradient smash fresh cream cake with chocolate drip and premium toppings.",
    fullDescription: "Modern smash fresh cream cake featuring purple-teal gradient frosting, rich chocolate drip, premium chocolate toppings and rose piping, perfect for stylish birthdays.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, dark chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy, nuts",
    rating: 4.8,
    reviewCount: 62,
    tags: ["fresh cream", "drip cake", "chocolate", "smash cake"],
    size: "6 INCH"
},
{
    id: 169,
    name: "Cartoon Tractor Smash Fresh Cream Cake",
    price: 198.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK188.webp",
    description: "Cute cartoon tractor themed smash fresh cream cake for kids birthday.",
    fullDescription: "Fun rectangular smash fresh cream cake featuring colourful cartoon tractor illustration, green landscape, musical notes and playful outdoor elements for young children.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, edible image print",
    weight: "8x8 inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 49,
    tags: ["fresh cream", "tractor cake", "kids cake", "smash cake"],
    size: "8 INCH"
},
{
    id: 170,
    name: "Mouse One-Year Smash Fresh Cream Cake",
    price: 195.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK189.webp",
    description: "One-year-old mouse themed smash fresh cream cake with cheese elements.",
    fullDescription: "Cute mouse smash fresh cream cake designed for first birthday celebrations, featuring cheese decorations, soft pastel colors and cheerful baby-friendly styling.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate",
    weight: "6-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 57,
    tags: ["fresh cream", "1st birthday", "mouse cake", "smash cake"],
    size: "6 INCH"
},
{
    id: 171,
    name: "Baby Rainbow Friends Smash Fresh Cream Cake",
    price: 228.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK190.webp",
    description: "Baby rainbow themed smash fresh cream cake with animal characters.",
    fullDescription: "Adorable baby smash fresh cream cake featuring rainbow arch, cloud elements, monkey, elephant and duck characters, perfect for toddler birthday celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate",
    weight: "7-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 64,
    tags: ["fresh cream", "baby cake", "rainbow", "smash cake"],
    size: "7 INCH"
},
{
    id: 172,
    name: "Pikachu Family Smash Fresh Cream Cake",
    price: 248.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK191.webp",
    description: "Pokemon Pikachu family smash fresh cream cake with gold decorations.",
    fullDescription: "Fun Pokemon themed smash fresh cream cake featuring multiple Pikachu figures, golden stars and lucky elements designed for energetic kids birthday parties.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate",
    weight: "7-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 5.0,
    reviewCount: 78,
    tags: ["fresh cream", "pikachu", "pokemon", "smash cake"],
    size: "7 INCH"
},
{
    id: 173,
    name: "Prosperity Tiger Smash Fresh Cream Cake",
    price: 258.00,
    category: "cake",
    subcategory: "fresh-cream",
    image: "cake/Fresh Cream Cake/FK195.webp",
    description: "Chinese prosperity tiger themed smash fresh cream cake with gold coins.",
    fullDescription: "Festive smash fresh cream cake featuring cute tiger character, gold ingots, prosperity coins and lucky red accents, perfect for festive birthdays and family celebrations.",
    ingredients: "Flour, eggs, sugar, butter, fresh cream, chocolate",
    weight: "7-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 73,
    tags: ["fresh cream", "tiger cake", "chinese theme", "smash cake"],
    size: "7 INCH"
},



        

            

            // Little Series
            {
    id: 197,
    name: "Fig Whisper Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE A.webp",
    description: "Delicate fresh cream little cake crowned with juicy figs and cream swirls.",
    fullDescription: "A petite celebration cake finished with soft white cream piping and generous wedges of sweet, ruby-centered figs. The natural fruit colours and simple “Happy Birthday” message make it perfect for someone who enjoys a clean, elegant style with a touch of freshness.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, fresh figs, vanilla, gelatin (stabiliser)",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 21,
    tags: ["little series", "fig", "fresh cream", "elegant"],
    size: "5 INCH"
},
{
    id: 198,
    name: "Macaron Citrus Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE B.webp",
    description: "Textured caramel-tone cake topped with macarons and dried citrus slices.",
    fullDescription: "This stylish little cake features soft rippled sides, a spiral cream top and a trio of macarons sitting alongside sun-dried orange slices and berries. Finished with rosemary sprigs and gold pearls, it feels like a café-style dessert in a perfectly sized birthday cake.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, almonds, macarons, dried orange, berries, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy, nuts (almond)",
    rating: 4.8,
    reviewCount: 24,
    tags: ["little series", "macaron", "citrus", "premium"],
    size: "5 INCH"
},
{
    id: 199,
    name: "Pink Ribbon Fig Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE C.webp",
    description: "Romantic fig and macaron little cake tied with a pink satin ribbon.",
    fullDescription: "A charming white cream cake decorated with fresh figs, strawberries, blueberries and soft pink macarons arranged in a full crown on top. Wrapped with a satin bow around the base, it’s an ideal choice for birthdays, anniversaries or any sweet surprise for someone special.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, figs, strawberries, blueberries, macarons, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy, nuts (may contain traces in macarons)",
    rating: 4.8,
    reviewCount: 26,
    tags: ["little series", "fig", "macaron", "romantic"],
    size: "5 INCH"
},
{
    id: 200,
    name: "Red Heart Splash Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE D.webp",
    description: "White cream cake with a bold red glaze and twin hearts on top.",
    fullDescription: "This minimalist little cake is finished with a glossy red ‘painted’ pool on the surface, sprinkled with tiny silver pearls and decorated with two puffed hearts. Scattered mini heart details around the side make it a sweet choice for Valentine’s, anniversaries or confessions of love.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, white chocolate, food colouring, gelatin (for glaze)",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 19,
    tags: ["little series", "romantic", "heart", "red"],
    size: "5 INCH"
},
{
    id: 201,
    name: "Blueberry Heart Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE E.webp",
    description: "Petite blueberry cake with blue cream splash and tiny heart accents.",
    fullDescription: "A bright blue glaze sits on top of this smooth white cake, carrying the hand-piped birthday message and plump blueberries. Small blue heart details and sugar pearls dance around the sides, giving a cheerful yet simple look that suits both adults and kids.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, blueberries, vanilla, food colouring",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 17,
    tags: ["little series", "blueberry", "minimal", "fresh cream"],
    size: "5 INCH"
},
{
    id: 202,
    name: "Golden Chocolate Drip Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE F.webp",
    description: "Rich chocolate drip cake loaded with gold-wrapped chocolates and cookies.",
    fullDescription: "This indulgent little cake is coated in white cream and finished with a dark chocolate drip that runs elegantly down the sides. The top is packed with assorted chocolates, golden coins, sandwich cookies and berries, creating a luxurious dessert table centerpiece in a compact size.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, dark chocolate, assorted chocolates, sandwich cookies, cocoa powder",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy, soy; may contain nuts",
    rating: 4.9,
    reviewCount: 28,
    tags: ["little series", "chocolate", "drip cake", "luxury"],
    size: "5 INCH"
},
{
    id: 203,
    name: "Citrus Garden Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE G.webp",
    description: "Fresh cream citrus cake topped with fruits, herbs and chocolate pieces.",
    fullDescription: "Inspired by a sunny fruit garden, this cake is decorated with figs, strawberries, blueberries, dried orange slices and chocolate blocks arranged on a smooth white surface. Yellow brush-stroke accents on the sides give it a playful, modern look while keeping the overall design light and refreshing.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, figs, strawberries, blueberries, orange slices, dark chocolate, rosemary, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 20,
    tags: ["little series", "citrus", "mixed fruit", "fresh cream"],
    size: "5 INCH"
},
{
    id: 204,
    name: "Sunny Daisy Citrus Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE H.webp",
    description: "Yellow ombré citrus cake decorated with daisies and dried orange wheels.",
    fullDescription: "This cheerful cake features a white-to-yellow ombré finish and a bright yellow ‘painted’ top panel framed with tiny white daisies and gold sprinkles. Large dried orange slices complete the summery look, making it perfect for sunshine lovers and casual birthday gatherings.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, orange zest, dried orange, food colouring, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 22,
    tags: ["little series", "citrus", "daisy", "summer"],
    size: "5 INCH"
},
{
    id: 205,
    name: "Matcha Strawberry Meadow Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE I.webp",
    description: "Matcha cream little cake topped with strawberries and daisy flowers.",
    fullDescription: "A cosy green ‘meadow’ of fine matcha crumbs covers the top of this petite cake, dotted with fresh strawberries and piped green cream peaks. Mini white daisies add a cute garden feel, making it a lovely option for tea lovers and those who enjoy less-sweet flavours.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, matcha powder, strawberries, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 23,
    tags: ["little series", "matcha", "strawberry", "tea-inspired"],
    size: "5 INCH"
},
{
    id: 206,
    name: "Rustic Blueberry Swirl Little Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE N.webp",
    description: "Rustic-style cream cake with caramel swirl top and blueberries.",
    fullDescription: "This petite cake has a warm beige tone with subtle horizontal lines and a hypnotic caramel cream swirl on top. Blueberries, coconut flakes and small green leaves circle the edge, giving it a handcrafted café vibe that’s perfect for simple, classy celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, blueberries, caramel or brown sugar cream, desiccated coconut, vanilla",
    weight: "5-inch petite cake (serves 3–5)",
    allergens: "Contains gluten, eggs, dairy, coconut",
    rating: 4.7,
    reviewCount: 18,
    tags: ["little series", "blueberry", "rustic", "café style"],
    size: "5 INCH"
},
{
    id: 207,
    name: "Little Lavender Berry Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE Q.webp",
    description: "Lavender-toned petite blueberry cake with soft cream swirls.",
    fullDescription: "A dreamy petite cake frosted in pastel lavender cream and crowned with fresh blueberries, white and purple whipped peaks, and tiny green leaves for a garden-style finish. Lightly sweet and refreshing, this cake is perfect for small birthday celebrations, afternoon tea, or anyone who loves a soft berry flavour with an elegant look.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, blueberries, vanilla, food colouring",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 21,
    tags: ["little series", "blueberry", "lavender", "petite cake"],
    size: "4 INCH"
},
{
    id: 208,
    name: "Little Nutty Biscuit Crunch",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE R.webp",
    description: "Caramel-toned petite cake topped with biscuits, nuts and berries.",
    fullDescription: "This petite cake features a smooth caramel-coloured cream and a generous topping of crunchy biscuits, roasted almonds, blueberries and dainty whipped cream kisses. Finished with chopped nuts around the base, it combines creamy texture with satisfying crunch, making it a cosy and flavourful choice for birthdays or casual celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, almonds, biscuits, blueberries, vanilla",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy, nuts",
    rating: 4.7,
    reviewCount: 19,
    tags: ["little series", "nutty", "biscuit", "caramel"],
    size: "4 INCH"
},
{
    id: 209,
    name: "Little Vintage Cherry Cake",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE U.webp",
    description: "Retro-style pink cherry cake with piped cream borders.",
    fullDescription: "A nostalgic petite cake in blush pink, decorated with lattice piping on top, scalloped cream borders and tiny cherry motifs all around the sides. Cute, playful and very photogenic, this design is perfect for vintage-style birthdays, bestie celebrations or anyone who loves a classic cherry look with light creamy sweetness.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, cherry flavouring, food colouring",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 23,
    tags: ["little series", "cherry", "vintage", "korean style"],
    size: "4 INCH"
},
{
    id: 210,
    name: "Little Strawberry Drip Delight",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE X.webp",
    description: "Petite strawberry cake with white chocolate drip and ruby chunks.",
    fullDescription: "This charming petite cake is layered with strawberry cream and finished with a soft pink frosting, white chocolate-style drip and fresh strawberries on top. Ruby chocolate pieces and cream rosettes add texture and colour, creating a sweet and girly centrepiece for birthdays, anniversaries or any strawberry lover.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, strawberries, white chocolate, ruby chocolate, vanilla",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy, soy",
    rating: 4.9,
    reviewCount: 27,
    tags: ["little series", "strawberry", "drip cake", "pink"],
    size: "4 INCH"
},
{
    id: 211,
    name: "Little Cocoa Berry Drip",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE Y.webp",
    description: "Chocolate-strawberry petite cake with dark drip and cocoa dust.",
    fullDescription: "A rich petite cake frosted in dusty rose cream and finished with a dark chocolate drip, fresh strawberries and blueberries on top. Light cocoa dust and curved chocolate decorations give it a modern bistro-style look, ideal for those who enjoy a balance of fruity freshness and deep chocolate notes in a smaller size.",
    ingredients: "Flour, sugar, eggs, butter, cocoa powder, fresh cream, strawberries, blueberries, dark chocolate",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy, soy",
    rating: 4.7,
    reviewCount: 20,
    tags: ["little series", "chocolate", "strawberry", "drip cake"],
    size: "4 INCH"
},
{
    id: 212,
    name: "Little Cookies & Cream Drip",
    price: 68.00,
    category: "cake",
    subcategory: "little",
    image: "cake/Little Series/LITTLE Z.webp",
    description: "Petite cookies and cream cake with chocolate drip and mini biscuits.",
    fullDescription: "This cookies and cream inspired petite cake is frosted with speckled Oreo-style cream and topped with a glossy chocolate drip. Mini sandwich biscuits, whipped swirls and tiny chocolate pearls complete the decoration, making it a fun yet classy choice for cookie lovers who want a compact celebration cake.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, cocoa powder, sandwich biscuits, chocolate, vanilla",
    weight: "4-inch petite cake (serves 2–4)",
    allergens: "Contains gluten, eggs, dairy, soy",
    rating: 4.8,
    reviewCount: 24,
    tags: ["little series", "cookies and cream", "oreo", "drip cake"],
    size: "4 INCH"
},



            // Strawberry Flavour
{
    id: 93,
    name: "PINK CELEBRATION TIER",
    price: 88.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Birthday Cake.webp",
    description: "A two-tier pink and white strawberry-themed celebration cake.",
    fullDescription: "A beautifully crafted two-tier cake featuring soft pink rosettes, pearl piping, and elegant quilted patterns. Perfect for birthdays and grand celebrations with a delightful strawberry-inspired finish.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, strawberry essence, food coloring",
    weight: "2-Tier (Serves 15–20 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 42,
    tags: ["strawberry", "tier", "celebration"],
    size: "2 TIER"
},
{
    id: 94,
    name: "ROSE HEART STRAWBERRY CAKE",
    price: 72.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Designer Rose Heart Cake.jpg",
    description: "Romantic heart-shaped cake with two-tone strawberry rosettes.",
    fullDescription: "A heart-shaped cake decorated with luscious pink and white buttercream rosettes, creating a dreamy floral texture. Ideal for anniversaries, birthdays, or romantic surprises.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, strawberry puree, food coloring",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 37,
    tags: ["heart", "romantic", "strawberry"],
    size: "1 KG"
},
{
    id: 95,
    name: "STRAWBERRY HEART DRIP CAKE",
    price: 68.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Five Star Strawberry Heart Cake.jpg",
    description: "Heart-shaped cake with glossy strawberry frosting and chocolate décor.",
    fullDescription: "A striking heart cake featuring a smooth strawberry glaze, chocolate drip accent, and striped chocolate heart toppers. Sweet, stylish, and perfect for special occasions.",
    ingredients: "Flour, sugar, eggs, butter, strawberry flavor, chocolate, food coloring",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 33,
    tags: ["heart", "drip", "strawberry"],
    size: "1 KG"
},
{
    id: 96,
    name: "STRAWBERRY DELIGHT ROUND CAKE",
    price: 65.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Five Star Strawberry.jpg",
    description: "Glossy strawberry cake topped with elegant chocolate decorations.",
    fullDescription: "A premium strawberry-glazed cake drizzled with artistic chocolate elements, finished with cherries and decorative sticks. A perfect blend of sweetness and sophistication.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, strawberry glaze, chocolate",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 29,
    tags: ["strawberry", "glaze", "premium"],
    size: "1 KG"
},
{
    id: 97,
    name: "HEARTS & ROSES STRAWBERRY CAKE",
    price: 66.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Happy Birthday Strawberry Cake.jpg",
    description: "Romantic strawberry-themed celebration cake with hearts and rosettes.",
    fullDescription: "Featuring a smooth marbled pink surface, delicate strawberry drip, and charming heart-shaped chocolate pieces, this cake is made for heartfelt celebrations.",
    ingredients: "Flour, sugar, eggs, butter, cream, strawberry syrup, chocolate",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 31,
    tags: ["hearts", "strawberry", "romantic"],
    size: "1 KG"
},
{
    id: 98,
    name: "STRAWBERRY FANTASY DRIP CAKE",
    price: 68.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Heartwarming-strawberry.jpg",
    description: "A white and red strawberry cake with a smooth dripping glaze.",
    fullDescription: "A classic strawberry cake topped with a glossy red glaze, decorative strawberry topper, and swirl piping around the edges. A bright and cheerful cake for all strawberry lovers.",
    ingredients: "Flour, sugar, eggs, butter, cream, strawberry essence, food coloring",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 27,
    tags: ["classic", "drip", "strawberry"],
    size: "1 KG"
},
{
    id: 99,
    name: "PINK SWIRL STRAWBERRY CAKE",
    price: 62.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Strawberry Cake - Midnight Delivery.jpg",
    description: "Bright strawberry cake with soft pastel topping and elegant piping.",
    fullDescription: "A beautifully piped strawberry cake featuring soft pink swirls, smooth glaze, and charming decorative chocolate accents. Light, creamy, and perfect for gifting.",
    ingredients: "Flour, sugar, eggs, butter, cream, strawberry flavoring",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 22,
    tags: ["strawberry", "pink", "smooth"],
    size: "1 KG"
},
{
    id: 100,
    name: "STRAWBERRY LOVE MOM CAKE",
    price: 70.00,
    category: "cake",
    subcategory: "strawberry",
    image: "cake/Strawberry Flavour/Strawberry Cream cake For Mom.jpg",
    description: "A soft pink strawberry cream cake specially designed for moms.",
    fullDescription: "A meaningful strawberry cake with elegant cream drops, chocolate sticks, and a loving message design. Ideal as a Mother’s Day gift or a sweet surprise for someone special.",
    ingredients: "Flour, sugar, eggs, butter, whipping cream, strawberry flavor, chocolate",
    weight: "1KG (Serves 8–10 people)",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 40,
    tags: ["mom", "strawberry", "special"],
    size: "1 KG"
},


// The Animal Series
// Mini Character Design Cake (3 Inch) – price 65.00
{
    id: 119,
    name: "Curly Sheep Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Curly Sheep.webp",
    description: "Fluffy sheep-themed cake with curly cream texture and a soft pastel animal face.",
    fullDescription: "A charming 5-inch animal series cake designed as a curly sheep, covered in layers of fluffy white fresh cream and finished with soft pastel facial details. Perfect for animal-themed parties and kids celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 17,
    tags: ["animal", "sheep", "kids", "cute"],
    size: "5 INCH"
},
{
    id: 120,
    name: "Green Dinosaur Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Green Dinosaur.webp",
    description: "Bright green dinosaur cake decorated with colourful spikes and a playful expression.",
    fullDescription: "This 5-inch dinosaur animal cake is decorated with vibrant green cream, cheerful spikes and a smiling dinosaur face. A fun centrepiece for kids birthdays and dinosaur-themed parties.",
    ingredients: "Flour, sugar, eggs, butter, cream, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 14,
    tags: ["animal", "dinosaur", "kids", "birthday"],
    size: "5 INCH"
},
{
    id: 121,
    name: "Green Snake Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Green Snake.webp",
    description: "Mint green snake cake with playful spots and a cheeky tongue detail.",
    fullDescription: "A lively 5-inch snake animal cake decorated in soft mint green with bold dots and a cheerful snake expression. Perfect for zodiac themes and jungle-style birthday parties.",
    ingredients: "Flour, sugar, eggs, butter, cream, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 13,
    tags: ["animal", "snake", "zodiac", "kids"],
    size: "5 INCH"
},
{
    id: 122,
    name: "Grey Mouse Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Grey Mouse.webp",
    description: "Soft grey mouse cake with rounded ears and gentle blush details.",
    fullDescription: "This 5-inch mouse animal cake features smooth grey cream, delicate rounded ears and blush accents, creating a sweet and minimalist animal design suitable for all ages.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 15,
    tags: ["animal", "mouse", "cute", "kids"],
    size: "5 INCH"
},
{
    id: 123,
    name: "Milk Tea Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Milk Tea.webp",
    description: "Bubble milk tea inspired cake with boba pearls and a smiling cup character.",
    fullDescription: "A fun 5-inch animal cake inspired by classic bubble milk tea, featuring layered cream textures, boba-style decorations and an adorable smiling cup character.",
    ingredients: "Flour, sugar, eggs, butter, cream, cocoa powder, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 20,
    tags: ["animal", "milk tea", "boba", "cute"],
    size: "5 INCH"
},
{
    id: 124,
    name: "Moo Moo Cow Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Moo Moo Cow.webp",
    description: "Classic black-and-white cow cake with soft cream texture and gentle pink accents.",
    fullDescription: "This 5-inch cow animal cake features smooth white cream with signature black cow patches, golden horn details and a soft pink muzzle, making it perfect for farm-themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, cream, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 18,
    tags: ["animal", "cow", "farm", "kids"],
    size: "5 INCH"
},
{
    id: 125,
    name: "Orange Tiger Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Orange Tiger.webp",
    description: "Bright orange tiger cake with playful stripes and soft rounded facial details.",
    fullDescription: "A bold 5-inch tiger animal cake decorated in vibrant orange tones with hand-piped stripes, rounded ears and sweet facial features, great for jungle or zodiac-themed parties.",
    ingredients: "Flour, sugar, eggs, butter, cream, food colouring, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 12,
    tags: ["animal", "tiger", "zodiac", "birthday"],
    size: "5 INCH"
},
{
    id: 126,
    name: "Oreo Monster Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Oreo Monster.webp",
    description: "Cookies-and-cream monster cake with dramatic drip icing and big cartoon eyes.",
    fullDescription: "A playful 5-inch monster-style animal cake packed with Oreo crumbs, dripping white chocolate ganache and oversized cartoon eyes, loved by kids and chocolate fans.",
    ingredients: "Flour, sugar, eggs, butter, Oreo biscuits, cream, white chocolate",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy, soy",
    rating: 4.8,
    reviewCount: 19,
    tags: ["animal", "oreo", "monster", "kids"],
    size: "5 INCH"
},
{
    id: 127,
    name: "Pastel Unicorn Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Pastel Unicorn.webp",
    description: "Dreamy unicorn cake with pastel rosette mane and a delicate golden horn.",
    fullDescription: "A beautifully styled 5-inch unicorn animal cake finished with pastel rosettes, a shimmering gold horn and soft facial details. Perfect for fantasy-themed and girls birthday celebrations.",
    ingredients: "Flour, sugar, eggs, butter, cream, food colouring, fondant decorations",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 24,
    tags: ["animal", "unicorn", "pastel", "kids"],
    size: "5 INCH"
},
{
    id: 128,
    name: "Pink Piggy Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Pink Pig.webp",
    description: "Soft pink pig cake with rounded ears, tiny snout and sweet blushing cheeks.",
    fullDescription: "This 5-inch pig animal cake is frosted in smooth pink cream with adorable pig features, creating a friendly, cheerful design perfect for children’s birthday parties.",
    ingredients: "Flour, sugar, eggs, butter, cream, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 16,
    tags: ["animal", "pig", "cute", "birthday"],
    size: "5 INCH"
},
{
    id: 129,
    name: "White Rabbit Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - White Rabbit.webp",
    description: "Elegant white rabbit cake with long ears and gentle facial expression.",
    fullDescription: "This 5-inch rabbit animal cake features a smooth white cream finish with delicate long ears and sweet facial details, making it a lovely centrepiece for kids and soft-theme celebrations.",
    ingredients: "Flour, sugar, eggs, butter, milk, cream",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 22,
    tags: ["animal", "rabbit", "kids", "cute"],
    size: "5 INCH"
},
{
    id: 130,
    name: "Yellow Chick Animal Cake",
    price: 138.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/Mini Character Design Cake 3 Inch - Yellow Chick.webp",
    description: "Bright yellow chick cake with rounded body shape and cheerful expression.",
    fullDescription: "A joyful 5-inch chick animal cake decorated in vibrant yellow cream with cute chick facial details, perfect for children’s birthdays and happy celebrations.",
    ingredients: "Flour, sugar, eggs, butter, milk, cream",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 19,
    tags: ["animal", "chick", "kids", "birthday"],
    size: "5 INCH"
},
{
    id: 189,
    name: "The Chick Animal Cake",
    price: 98.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE CHICK.webp",
    description: "Bright yellow chick-shaped animal cake with tiny wings and rosy cheeks.",
    fullDescription: "This cheerful chick-shaped animal cake features a smooth yellow outer layer with adorable wings, a tiny beak and soft blush cheeks. Designed to look lively and playful, it is perfect for kids’ birthdays, baby showers and animal-themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring, white chocolate",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 23,
    tags: ["animal", "chick", "cute", "kids"],
    size: "5 INCH"
},
{
    id: 190,
    name: "The Deer Animal Cake",
    price: 108.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE DEER.webp",
    description: "Gentle deer-inspired animal cake with antlers and soft cream details.",
    fullDescription: "Inspired by a gentle forest deer, this animal-themed cake features soft brown tones, delicate antlers and smooth cream finishing. The calm and sweet expression makes it a perfect choice for woodland-themed parties, birthdays and nature lovers.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, chocolate, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 27,
    tags: ["animal", "deer", "forest", "cute"],
    size: "5 INCH"
},
{
    id: 191,
    name: "The Fox Animal Cake",
    price: 108.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE FOX.webp",
    description: "Sleepy fox animal cake decorated with floral crown details.",
    fullDescription: "This charming fox cake is beautifully decorated with a sleeping expression, soft orange tones and a delicate floral crown on its head. Elegant yet cute, it is ideal for birthdays, baby showers and pastel animal-themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 21,
    tags: ["animal", "fox", "floral", "cute"],
    size: "5 INCH"
},
{
    id: 192,
    name: "The Monkey Animal Cake",
    price: 108.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE MONKEY.webp",
    description: "Playful monkey-shaped animal cake with round ears and blushing cheeks.",
    fullDescription: "This fun monkey cake features wide sparkling eyes, round ears and soft blush cheeks for a lively and friendly look. Its playful design makes it a favourite for kids’ parties, jungle themes and cheerful celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, milk chocolate",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 20,
    tags: ["animal", "monkey", "jungle", "kids"],
    size: "5 INCH"
},
{
    id: 193,
    name: "The Penguin Animal Cake",
    price: 118.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE PENGUIN.webp",
    description: "Cool blue penguin animal cake with sailor hat styling.",
    fullDescription: "Designed with a fun sailor theme, this penguin cake features a smooth blue body, white belly, yellow beak and a cute little hat on top. A perfect choice for winter themes, baby birthdays and playful animal lovers.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring, white chocolate",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 29,
    tags: ["animal", "penguin", "cute", "kids"],
    size: "5 INCH"
},
{
    id: 194,
    name: "The Puppy Animal Cake",
    price: 108.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE PUPPY.webp",
    description: "Smiling puppy-shaped animal cake with playful expression.",
    fullDescription: "This adorable puppy cake features a soft golden-brown finish, blushing cheeks and a big friendly smile. Designed to melt hearts instantly, it is perfect for dog lovers, kids’ birthdays and pet-themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 26,
    tags: ["animal", "puppy", "cute", "pet"],
    size: "5 INCH"
},
{
    id: 195,
    name: "The Sheep Animal Cake",
    price: 108.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE SHEEP.webp",
    description: "Fluffy sheep animal cake covered in soft whipped cream swirls.",
    fullDescription: "This sheep-inspired cake is decorated with layers of fluffy whipped cream to resemble soft wool, paired with a sweet smiling face and tiny ears. A gentle and comforting design ideal for baby showers and animal-themed parties.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, milk",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 25,
    tags: ["animal", "sheep", "fluffy", "cute"],
    size: "5 INCH"
},
{
    id: 196,
    name: "The Unicorn Animal Cake",
    price: 128.00,
    category: "cake",
    subcategory: "animal",
    image: "cake/The Animal Series/THE UNICORN.webp",
    description: "Dreamy unicorn animal cake with pastel rosettes and golden horn.",
    fullDescription: "This magical unicorn cake features a smooth white finish, closed dreamy eyes, pastel rosette mane and a shimmering golden horn. Soft, elegant and charming, it is perfect for birthdays, girls’ parties and fantasy-themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, fresh cream, food colouring, white chocolate",
    weight: "5-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 5.0,
    reviewCount: 34,
    tags: ["animal", "unicorn", "magical", "pastel"],
    size: "5 INCH"
},



            // Vanilla Flavour
            // Vanilla Flavour
{
    id: 101,
    name: "SILKEN VANILLA DRIP",
    price: 68.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/choco vanilla.webp",
    description: "Smooth vanilla cake with a glossy white drip and delicate chocolate accents.",
    fullDescription: "SILKEN VANILLA DRIP pairs a tender vanilla sponge with velvety vanilla cream and a refined white drip finish, accented by delicate chocolate decor for a subtle contrast. Ideal for understated celebrations and elegant dessert tables.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, fresh cream, white chocolate",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.8,
    reviewCount: 28,
    tags: ["vanilla", "drip", "elegant"],
    size: "1 KG"
},
{
    id: 102,
    name: "PENGUIN VANILLA DREAM",
    price: 72.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Dripping Penguin Vanilla.jpg",
    description: "Cute penguin-themed vanilla cake with blue-and-white drip effect.",
    fullDescription: "PENGUIN VANILLA DREAM features a smooth vanilla buttercream with playful blue accents and charming penguin toppers. A fun, whimsical choice for kids’ birthdays and themed celebrations.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, food coloring, fondant toppers",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 22,
    tags: ["vanilla", "kids", "theme"],
    size: "1 KG"
},
{
    id: 103,
    name: "VANILLA CHOCOLATE SWIRL",
    price: 66.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Flavorsome Vanilla Chocolate.jpg",
    description: "Vanilla cake with chocolate swirl details and elegant piping.",
    fullDescription: "VANILLA CHOCOLATE SWIRL combines classic vanilla sponge with artistic chocolate swirls and piped cream, delivering a balanced taste and a polished presentation suitable for many occasions.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, chocolate, fresh cream",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.7,
    reviewCount: 24,
    tags: ["vanilla", "chocolate", "classic"],
    size: "1 KG"
},
{
    id: 104,
    name: "FLORAL VANILLA CREAM",
    price: 70.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Gesture of Floral Vanilla Cream.jpg",
    description: "Vanilla cake decorated with floral cream piping and pastel hues.",
    fullDescription: "FLORAL VANILLA CREAM showcases soft vanilla layers finished with elegant floral-style buttercream piping. Its delicate appearance makes it a lovely choice for birthdays, baby showers and garden-themed events.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, fresh cream, food coloring",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 26,
    tags: ["vanilla", "floral", "party"],
    size: "1 KG"
},
{
    id: 105,
    name: "GLOSS VANILLA GLAZE",
    price: 64.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/glaze vanilla.webp",
    description: "Classic vanilla cake with a shiny glaze and simple piped border.",
    fullDescription: "GLOSS VANILLA GLAZE is a minimalist yet elegant vanilla cake topped with a mirror-like glaze and finished with neat piped borders — perfect for those who prefer clean, modern styling.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, mirror glaze ingredients, fresh cream",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 18,
    tags: ["vanilla", "glaze", "minimal"],
    size: "1 KG"
},
{
    id: 106,
    name: "HOLI SPECIAL VANILLA",
    price: 75.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Holi Special Vanilla Photo.jpg",
    description: "Vibrant Holi-inspired vanilla cake with bright coloured drip and toppings.",
    fullDescription: "HOLI SPECIAL VANILLA is a festive take on classic vanilla, decorated with colourful glaze drips and cheerful accents. Designed for celebrations that call for bold visuals and joyful flavours.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, colored glaze, fresh cream",
    weight: "1.2 KG (Serves 10–12 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 30,
    tags: ["vanilla", "festival", "colorful"],
    size: "1.2 KG"
},
{
    id: 107,
    name: "LAVENDER VANILLA CAKE",
    price: 78.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Lavender Vanilla Cake.webp",
    description: "Light vanilla cake infused with subtle lavender notes and floral decor.",
    fullDescription: "LAVENDER VANILLA CAKE blends delicate lavender aroma with smooth vanilla cream for a refined, aromatic dessert. Finished with pastel piping, it’s ideal for tea parties and intimate gatherings.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, culinary lavender, fresh cream",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 21,
    tags: ["vanilla", "lavender", "aromatic"],
    size: "1 KG"
},
{
    id: 108,
    name: "PILOT KIDS VANILLA",
    price: 72.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Pilot Travel Cake For Kids.jpg",
    description: "Playful kids’ vanilla cake with travel/plane themed toppers.",
    fullDescription: "PILOT KIDS VANILLA is a fun themed vanilla cake decorated with playful airplane toppers and cloud accents — perfect for children’s birthdays and travel-loving little ones.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, fondant toppers, fresh cream",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 19,
    tags: ["kids", "vanilla", "theme"],
    size: "1 KG"
},
{
    id: 109,
    name: "ROSE VANILLA ELEGANCE",
    price: 76.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/rose vanilla.webp",
    description: "Simple stacked vanilla cake adorned with fresh rose blossoms.",
    fullDescription: "ROSE VANILLA ELEGANCE is a minimalist layered vanilla cake finished with smooth buttercream and topped with fresh roses for a romantic, sophisticated presentation — perfect for intimate occasions.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, fresh cream, fresh roses (decoration)",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 27,
    tags: ["vanilla", "rose", "elegant"],
    size: "1 KG"
},
{
    id: 110,
    name: "SNOWMAN VANILLA FUN",
    price: 65.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/snowman vanilla.webp",
    description: "Bright blue vanilla cake with cute snowman-themed toppers.",
    fullDescription: "SNOWMAN VANILLA FUN features a cheerful blue finish with snowy piped details and adorable snowman decorations — a playful choice for winter birthdays or festive children’s parties.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, food coloring, fondant toppers",
    weight: "1 KG (Serves 8–10 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 16,
    tags: ["vanilla", "kids", "festive"],
    size: "1 KG"
},
// Vanilla Flavour
{
    id: 111,
    name: "Sprinkles Vanilla Cake",
    price: 48.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Sprinkles vanilla.webp",
    description: "Soft vanilla cake decorated with colorful sprinkles and fresh fruits.",
    fullDescription: "A smooth vanilla cake topped with assorted fruits, colorful sprinkles, and chocolate chips, perfect for celebrations.",
    ingredients: "Flour, eggs, sugar, vanilla extract, cream, sprinkles, fruits",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 31,
    tags: ["vanilla", "sprinkles", "fruit"],
    size: "8 INCH"
},
{
    id: 112,
    name: "Tropical Fruit & Almond Vanilla Cake",
    price: 58.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Tropical Fruit n Almond Cake.webp",
    description: "Vanilla cake topped with tropical fruits and crunchy almonds.",
    fullDescription: "A refreshing vanilla cream cake filled and topped with tropical fruits like kiwi, dragon fruit, oranges, cherries, and coated with almond flakes.",
    ingredients: "Flour, eggs, sugar, cream, tropical fruits, almond flakes",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy, nuts",
    rating: 4.7,
    reviewCount: 42,
    tags: ["vanilla", "fruit", "almond"],
    size: "8 INCH"
},
{
    id: 113,
    name: "Classic Vanilla Cream Cake",
    price: 45.00,
    category: "cake",
    subcategory: "vanilla",
    image: "cake/Vanilla Flavour/Vanilla flavour cake.webp",
    description: "Elegant vanilla cream cake with minimal decoration.",
    fullDescription: "A traditional and smooth vanilla cake topped with a single cherry and chocolate decoration, giving a simple yet classic look.",
    ingredients: "Flour, sugar, eggs, butter, vanilla extract, cream",
    weight: "8-inch",
    allergens: "Contains gluten, eggs, dairy",
    rating: 4.5,
    reviewCount: 29,
    tags: ["vanilla", "classic", "cream"],
    size: "8 INCH"
},


            

            // Wedding Gift Packages
            {
                id: 19,
                name: "Luxury Wedding Cake Package",
                price: 200.00,
                category: "cake",
                subcategory: "wedding",
                image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                description: "Premium wedding cake with customization",
                fullDescription: "Custom-designed wedding cake package including consultation, design, and delivery.",
                ingredients: "Premium ingredients based on selection",
                weight: "Custom",
                allergens: "Varies by selection",
                rating: 4.9,
                reviewCount: 15,
                tags: ["wedding", "luxury", "custom"],
                size: "CUSTOM"
            },

            // Bread products (existing)

            // =====================
// BREAD – SOURDOUGH (ID 220–227)
// =====================

{
    id: 220,
    name: "Traditional Artisan Sourdough",
    price: 12.50,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/traditional-sourdough.jpg",
    description: "Classic artisan sourdough with a deep golden crust and airy crumb.",
    fullDescription: "Our Traditional Artisan Sourdough is naturally fermented using a mature starter, producing a crisp crust and light, open interior. Rich in flavour with a mild tang, this loaf is perfect for daily enjoyment or pairing with soups and spreads.",
    ingredients: "Bread flour, water, sea salt, sourdough starter",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 46,
    tags: ["artisan", "classic", "traditional"],
    size: "500G"
},
{
    id: 221,
    name: "Same Day Sourdough Bread",
    price: 11.90,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Same Day Sourdough Bread.webp",
    description: "Freshly baked sourdough with a lighter tang and soft interior.",
    fullDescription: "Baked and enjoyed on the same day, this sourdough offers a gentle acidity and soft crumb while still maintaining a crisp crust. Ideal for customers who prefer a milder sourdough flavour.",
    ingredients: "Bread flour, water, sea salt, sourdough starter",
    weight: "480g",
    allergens: "Contains gluten",
    rating: 4.6,
    reviewCount: 34,
    tags: ["fresh", "mild", "daily"],
    size: "480G"
},
{
    id: 222,
    name: "Basic Sourdough Boule",
    price: 11.50,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Basic Sourdough Boule.webp",
    description: "Simple and balanced sourdough boule with crunchy crust.",
    fullDescription: "This Basic Sourdough Boule focuses on clean flavour and traditional technique. Naturally leavened and slow fermented, it delivers a well-balanced tang with a chewy crumb and rustic appearance.",
    ingredients: "Bread flour, water, sea salt, sourdough starter",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.7,
    reviewCount: 29,
    tags: ["simple", "classic", "boule"],
    size: "500G"
},
{
    id: 223,
    name: "No-Knead Rustic Sourdough",
    price: 12.90,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Simple No-Knead Sourdough Bread.jpeg",
    description: "Rustic no-knead sourdough with open crumb and bold crust.",
    fullDescription: "Crafted using a no-knead method, this rustic sourdough develops flavour through long fermentation. The result is a deeply caramelised crust with a moist and airy interior.",
    ingredients: "Bread flour, water, sea salt, sourdough starter",
    weight: "520g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 41,
    tags: ["no-knead", "rustic", "artisan"],
    size: "520G"
},
{
    id: 224,
    name: "Overnight Fermented Sourdough",
    price: 13.50,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Overnight Sourdough Bread.jpg",
    description: "Slow overnight fermented sourdough with rich aroma.",
    fullDescription: "Fermented overnight to enhance complexity, this sourdough loaf offers a deeper tang and aromatic profile. A favourite among sourdough lovers who appreciate bold flavour.",
    ingredients: "Bread flour, water, sea salt, sourdough starter",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.9,
    reviewCount: 52,
    tags: ["overnight", "slow-fermented", "bold"],
    size: "500G"
},
{
    id: 225,
    name: "Rye Sourdough Bread",
    price: 14.50,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Rye Sourdough Bread.jpg",
    description: "Hearty rye sourdough with earthy flavour and dense crumb.",
    fullDescription: "Made with a blend of rye and wheat flour, this sourdough delivers earthy notes, chewy texture and a satisfying bite. Excellent with savoury toppings or smoked meats.",
    ingredients: "Rye flour, bread flour, water, sea salt, sourdough starter",
    weight: "520g",
    allergens: "Contains gluten",
    rating: 4.7,
    reviewCount: 27,
    tags: ["rye", "hearty", "artisan"],
    size: "520G"
},
{
    id: 226,
    name: "Oatmeal Sourdough Bread",
    price: 13.90,
    category: "bread",
    subcategory: "sourdough",
    image: "bread/Sourdough Bread/Oatmeal Sourdough Bread.jpg",
    description: "Soft sourdough enriched with oats for a nutty finish.",
    fullDescription: "This oatmeal sourdough combines rolled oats with natural fermentation, creating a moist crumb and subtle nutty sweetness. Ideal for breakfast toast or light sandwiches.",
    ingredients: "Bread flour, rolled oats, water, sea salt, sourdough starter",
    weight: "520g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 33,
    tags: ["oats", "soft", "nutty"],
    size: "520G"
},

// =====================
// BREAD – WHOLE GRAIN (ID 228–232)
// =====================

{
    id: 228,
    name: "Homestyle Whole Grain Loaf",
    price: 10.90,
    category: "bread",
    subcategory: "wholegrain",
    image: "bread/Whole Grain Bread/Homestyle Whole Grain Loaf.webp",
    description: "Soft and hearty whole grain loaf baked for everyday enjoyment.",
    fullDescription: "Our Homestyle Whole Grain Loaf is made with wholesome grains and baked to a soft, tender crumb. Mild in flavour and versatile, it is perfect for sandwiches, toast, or enjoying with butter.",
    ingredients: "Whole wheat flour, bread flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.6,
    reviewCount: 31,
    tags: ["homestyle", "soft", "daily"],
    size: "500G"
},
{
    id: 229,
    name: "Classic Multigrain Bread",
    price: 11.50,
    category: "bread",
    subcategory: "wholegrain",
    image: "bread/Whole Grain Bread/Multigrain Bread.webp",
    description: "Nutritious multigrain bread with a mix of wholesome grains.",
    fullDescription: "This Classic Multigrain Bread combines several grains to create a rich texture and nutty flavour. A balanced loaf that delivers both taste and nutrition in every slice.",
    ingredients: "Whole wheat flour, multigrain mix, water, yeast, sea salt",
    weight: "520g",
    allergens: "Contains gluten",
    rating: 4.7,
    reviewCount: 28,
    tags: ["multigrain", "nutritious", "hearty"],
    size: "520G"
},
{
    id: 230,
    name: "Honey Whole Wheat Bread",
    price: 11.90,
    category: "bread",
    subcategory: "wholegrain",
    image: "bread/Whole Grain Bread/Basic Honey Whole Wheat Bread.jpg",
    description: "Lightly sweetened whole wheat bread with natural honey.",
    fullDescription: "Made with whole wheat flour and a touch of natural honey, this bread offers a soft crumb with gentle sweetness. Ideal for breakfast toast or pairing with jams and spreads.",
    ingredients: "Whole wheat flour, honey, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 36,
    tags: ["honey", "whole wheat", "soft"],
    size: "500G"
},
{
    id: 231,
    name: "Classic Whole Wheat Bread",
    price: 10.50,
    category: "bread",
    subcategory: "wholegrain",
    image: "bread/Whole Grain Bread/Whole Wheat Bread.jpg",
    description: "Traditional whole wheat loaf with a balanced, nutty taste.",
    fullDescription: "This Classic Whole Wheat Bread delivers a wholesome flavour with a firm yet soft crumb. A reliable everyday loaf suitable for sandwiches, toast, and healthy meals.",
    ingredients: "Whole wheat flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.6,
    reviewCount: 24,
    tags: ["whole wheat", "classic", "healthy"],
    size: "500G"
},
{
    id: 232,
    name: "Whole Grain Seeded Bread",
    price: 12.90,
    category: "bread",
    subcategory: "wholegrain",
    image: "bread/Whole Grain Bread/Whole Grain Seeded Bread.jpg",
    description: "Whole grain loaf topped with mixed seeds for extra crunch.",
    fullDescription: "Packed with wholesome grains and finished with a generous topping of seeds, this loaf offers a crunchy crust and rich, nutty flavour. Perfect for those who enjoy texture and depth in their bread.",
    ingredients: "Whole wheat flour, mixed seeds, water, yeast, sea salt",
    weight: "540g",
    allergens: "Contains gluten, sesame seeds",
    rating: 4.8,
    reviewCount: 39,
    tags: ["seeded", "whole grain", "hearty"],
    size: "540G"
},

// =====================
// BREAD – ARTISAN (ID 233–239)
// =====================

{
    id: 233,
    name: "Crusty Artisan Bread",
    price: 12.50,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Crusty Artisan Bread.webp",
    description: "Crusty artisan loaf with a golden exterior and airy crumb.",
    fullDescription: "This Crusty Artisan Bread is baked at high heat to develop a deeply caramelised crust while keeping the inside light and chewy. A classic choice for dipping, sandwiches, or enjoying on its own.",
    ingredients: "Bread flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 42,
    tags: ["crusty", "artisan", "classic"],
    size: "500G"
},
{
    id: 234,
    name: "Gluten-Free Artisan Bread",
    price: 13.90,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Gluten Free Artisan Bread.jpg",
    description: "Handcrafted gluten-free artisan loaf with a crisp crust.",
    fullDescription: "Made with a carefully balanced gluten-free flour blend, this artisan loaf offers a crisp crust and soft interior while remaining light and flavourful.",
    ingredients: "Gluten-free flour blend, water, yeast, sea salt",
    weight: "450g",
    allergens: "Gluten-free",
    rating: 4.6,
    reviewCount: 26,
    tags: ["gluten-free", "artisan", "handmade"],
    size: "450G"
},
{
    id: 235,
    name: "Easy Homemade Artisan Bread",
    price: 11.90,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Easy Homemade Artisan Bread.webp",
    description: "Simple homemade-style artisan bread with rustic texture.",
    fullDescription: "This Easy Homemade Artisan Bread focuses on simplicity and flavour, producing a rustic loaf with a crisp crust and soft, open crumb. Perfect for everyday enjoyment.",
    ingredients: "Bread flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.7,
    reviewCount: 33,
    tags: ["homemade", "rustic", "artisan"],
    size: "500G"
},
{
    id: 236,
    name: "Jalapeño Cheese Artisan Bread",
    price: 14.50,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Jalapeno Cheese Artisan Bread.jpg",
    description: "Bold artisan bread filled with jalapeño and melted cheese.",
    fullDescription: "This flavour-packed artisan loaf combines spicy jalapeños with rich cheese, creating a savoury bread that is perfect for sharing or pairing with soups and dips.",
    ingredients: "Bread flour, cheese, jalapeño, water, yeast, sea salt",
    weight: "520g",
    allergens: "Contains gluten, dairy",
    rating: 4.9,
    reviewCount: 48,
    tags: ["jalapeno", "cheese", "bold"],
    size: "520G"
},
{
    id: 237,
    name: "Classic Artisan Loaf",
    price: 12.20,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Artisan loaf.webp",
    description: "Classic round artisan loaf with rustic scoring.",
    fullDescription: "A traditional artisan loaf featuring hand scoring and natural fermentation, resulting in a chewy crumb and deeply flavoured crust.",
    ingredients: "Bread flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.7,
    reviewCount: 35,
    tags: ["classic", "artisan", "rustic"],
    size: "500G"
},
{
    id: 238,
    name: "No-Knead Artisan Bread",
    price: 12.80,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Easy No-Knead Artisan Bread.jpg",
    description: "No-knead artisan bread with deep flavour and open crumb.",
    fullDescription: "Fermented slowly without kneading, this artisan bread develops a complex flavour profile and airy texture with minimal handling.",
    ingredients: "Bread flour, water, yeast, sea salt",
    weight: "520g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 40,
    tags: ["no-knead", "artisan", "slow-fermented"],
    size: "520G"
},
{
    id: 239,
    name: "Country-Style Artisan Bread",
    price: 12.60,
    category: "bread",
    subcategory: "artisan",
    image: "bread/Artisan Bread/Classic Artisan Country Bread.jpg",
    description: "Country-style artisan loaf with rustic crust and soft interior.",
    fullDescription: "Inspired by traditional country baking, this artisan bread features a crunchy crust and tender crumb, ideal for hearty meals and sharing.",
    ingredients: "Bread flour, water, yeast, sea salt",
    weight: "500g",
    allergens: "Contains gluten",
    rating: 4.8,
    reviewCount: 37,
    tags: ["country", "artisan", "rustic"],
    size: "500G"
},

// =====================
// BREAD – SWEET (ID 240–248)
// =====================

{
    id: 240,
    name: "Alsatian Kugelhopf Sweet Bread",
    price: 15.90,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Alsatian Kugelhopf Sweet Bread.webp",
    description: "Traditional Alsatian sweet bread with almonds and raisins.",
    fullDescription: "A classic European sweet bread baked in a ring mould, enriched with butter, eggs, raisins, and topped with sliced almonds for a rich yet delicate flavour.",
    ingredients: "Flour, butter, eggs, milk, sugar, raisins, almonds, yeast",
    weight: "600g",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.8,
    reviewCount: 44,
    tags: ["kugelhopf", "classic", "european"],
    size: "600G"
},
{
    id: 241,
    name: "Sweet Almond Braided Loaf",
    price: 14.50,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Sweet Almond Braided Loaf.jpg",
    description: "Soft braided sweet bread topped with sliced almonds.",
    fullDescription: "This beautifully braided loaf features a soft, fluffy crumb with a mild sweetness, finished with crunchy almond slices for extra texture.",
    ingredients: "Flour, butter, eggs, milk, sugar, almonds, yeast",
    weight: "550g",
    allergens: "Contains gluten, dairy, eggs, nuts",
    rating: 4.7,
    reviewCount: 38,
    tags: ["almond", "braided", "sweet"],
    size: "550G"
},
{
    id: 242,
    name: "Lemon Blueberry Sweet Bread",
    price: 13.90,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Lemon Blueberry Bread.jpg",
    description: "Moist sweet bread filled with blueberries and fresh lemon.",
    fullDescription: "A refreshing sweet loaf bursting with juicy blueberries and bright lemon flavour, finished with a light glaze for a balanced sweet-tart taste.",
    ingredients: "Flour, butter, eggs, sugar, blueberries, lemon, milk",
    weight: "500g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 52,
    tags: ["lemon", "blueberry", "fresh"],
    size: "500G"
},
{
    id: 243,
    name: "Classic Lemon Glazed Loaf",
    price: 13.50,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Starbucks Lemon Loaf.jpg",
    description: "Soft lemon loaf topped with smooth lemon glaze.",
    fullDescription: "Inspired by café-style baking, this lemon loaf delivers a tender crumb with vibrant citrus flavour, finished with a sweet and tangy glaze.",
    ingredients: "Flour, butter, eggs, sugar, lemon, milk",
    weight: "480g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 61,
    tags: ["lemon", "glazed", "cafe-style"],
    size: "480G"
},
{
    id: 244,
    name: "Moist Banana Sweet Bread",
    price: 12.90,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Moist Banana Bread.jpg",
    description: "Rich and moist banana bread with natural sweetness.",
    fullDescription: "Made with ripe bananas, this sweet bread is incredibly moist and comforting, perfect for breakfast, tea time, or dessert.",
    ingredients: "Flour, banana, butter, eggs, sugar",
    weight: "520g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 57,
    tags: ["banana", "moist", "classic"],
    size: "520G"
},
{
    id: 245,
    name: "Twisted Sweet Bread",
    price: 14.20,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Twisted Sweet Bread.jpg",
    description: "Soft twisted sweet bread with layered texture.",
    fullDescription: "This twisted sweet bread features soft layers and a lightly sweet flavour, making it perfect for sharing or enjoying with coffee.",
    ingredients: "Flour, butter, eggs, milk, sugar, yeast",
    weight: "550g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.7,
    reviewCount: 34,
    tags: ["twisted", "soft", "sweet"],
    size: "550G"
},
{
    id: 246,
    name: "Braided Sweet Yeast Bread",
    price: 14.80,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Braided Sweet Yeast Bread.webp",
    description: "Fluffy braided sweet bread made with yeast dough.",
    fullDescription: "A traditional braided yeast bread with a soft crumb and gentle sweetness, ideal for breakfast spreads or light desserts.",
    ingredients: "Flour, butter, eggs, milk, sugar, yeast",
    weight: "560g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 41,
    tags: ["braided", "yeast", "traditional"],
    size: "560G"
},
{
    id: 247,
    name: "Honey Sweet Bread Rolls",
    price: 13.90,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/BEST Honey Sweet Bread Rolls.jpg",
    description: "Soft and fluffy sweet rolls glazed with honey.",
    fullDescription: "These honey sweet bread rolls are light, fluffy, and gently sweetened, making them perfect as dinner rolls or a sweet snack.",
    ingredients: "Flour, honey, butter, eggs, milk, yeast",
    weight: "500g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.9,
    reviewCount: 46,
    tags: ["honey", "rolls", "soft"],
    size: "500G"
},
{
    id: 248,
    name: "Pulla Sweet Bread",
    price: 14.60,
    category: "bread",
    subcategory: "sweet",
    image: "bread/Sweet Bread/Pulla Bread.jpg",
    description: "Nordic sweet bread flavoured with cardamom.",
    fullDescription: "Pulla is a traditional Finnish sweet bread, lightly scented with cardamom and braided for a soft, aromatic loaf that pairs beautifully with coffee.",
    ingredients: "Flour, butter, eggs, milk, sugar, cardamom, yeast",
    weight: "550g",
    allergens: "Contains gluten, dairy, eggs",
    rating: 4.8,
    reviewCount: 39,
    tags: ["pulla", "cardamom", "nordic"],
    size: "550G"
},



            

            // Pastry products (existing)
            {
  id: 249,
  name: "Swiss Roll Croissant",
  price: 9.80,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Swiss Roll  croissant(1).jpg",
  description: "Flaky croissant with a unique rolled shape.",
  fullDescription: "This Swiss Roll Croissant features beautifully laminated layers rolled into a compact form, delivering a crisp exterior and a soft, buttery center with every bite.",
  ingredients: "Flour, butter, milk, sugar, yeast, eggs, salt",
  weight: "120g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 28,
  tags: ["croissant", "buttery", "artisan"],
  size: "120G"
}, 
{
  id: 250,
  name: "Croissant Bread Pudding",
  price: 12.90,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Croissant Bread Pudding.jpg",
  description: "Rich bread pudding made from croissants.",
  fullDescription: "A comforting dessert crafted from buttery croissants baked in a silky custard, offering a soft interior with a lightly caramelised top.",
  ingredients: "Croissants, milk, eggs, cream, sugar, vanilla",
  weight: "180g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 34,
  tags: ["dessert", "custard", "comfort"],
  size: "180G"
},
{
  id: 251,
  name: "Chocolate Filled Croissant",
  price: 8.90,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Chocolate-Filled Croissant.jpg",
  description: "Classic croissant filled with rich chocolate.",
  fullDescription: "A traditional French-style croissant generously filled with smooth, melted chocolate, balancing crisp layers with indulgent sweetness.",
  ingredients: "Flour, butter, chocolate, milk, sugar, yeast, eggs",
  weight: "110g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.9,
  reviewCount: 61,
  tags: ["chocolate", "croissant", "french"],
  size: "110G"
},
{
  id: 252,
  name: "Ham and Cheese Croissant",
  price: 13.50,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Ham and Cheese Croissant.jpg",
  description: "Savory croissant with ham and cheese.",
  fullDescription: "A hearty croissant layered with premium ham and melted cheese, baked until golden for a satisfying savory option.",
  ingredients: "Flour, butter, ham, cheese, milk, yeast, eggs",
  weight: "150g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 42,
  tags: ["savory", "ham", "cheese"],
  size: "150G"
},
{
  id: 253,
  name: "Nutella Croissant",
  price: 10.20,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/3-Ingredient Nutella Croissants.jpg",
  description: "Croissant filled with creamy Nutella.",
  fullDescription: "A flaky croissant filled with smooth Nutella, offering a rich hazelnut chocolate flavour wrapped in crisp, golden layers.",
  ingredients: "Flour, butter, Nutella, milk, yeast, eggs",
  weight: "115g",
  allergens: "Contains gluten, dairy, eggs, nuts",
  rating: 4.8,
  reviewCount: 55,
  tags: ["nutella", "sweet", "chocolate"],
  size: "115G"
},
{
  id: 254,
  name: "Chocolate Almond Croissant",
  price: 12.80,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Chocolate Almond Croissants.jpg",
  description: "Croissant topped with almonds and chocolate.",
  fullDescription: "A decadent croissant filled with chocolate and topped with toasted almonds, delivering a rich, nutty crunch in every bite.",
  ingredients: "Flour, butter, chocolate, almonds, milk, yeast, eggs",
  weight: "140g",
  allergens: "Contains gluten, dairy, eggs, nuts",
  rating: 4.7,
  reviewCount: 47,
  tags: ["almond", "chocolate", "croissant"],
  size: "140G"
},
{
  id: 255,
  name: "Classic French Croissant",
  price: 7.90,
  category: "pastry",
  subcategory: "croissant",
  image: "pastries/Croissants/Homemade French Croissants.jpg",
  description: "Traditional French butter croissant.",
  fullDescription: "A classic French croissant made with carefully laminated dough, featuring crisp layers outside and a light, airy interior.",
  ingredients: "Flour, butter, milk, sugar, yeast, salt",
  weight: "95g",
  allergens: "Contains gluten, dairy",
  rating: 4.9,
  reviewCount: 73,
  tags: ["classic", "buttery", "french"],
  size: "95G"
},

{
  id: 256,
  name: "Chocolate Cream Cheese Danish",
  price: 12.80,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Chocolate Cream Cheese Danish.jpg",
  description: "Flaky Danish pastry with chocolate and cream cheese.",
  fullDescription: "A buttery Danish layered with smooth cream cheese and rich chocolate, baked until golden and finished with a delicate crisp exterior.",
  ingredients: "Flour, butter, cream cheese, chocolate, sugar, eggs, milk",
  weight: "130g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 52,
  tags: ["danish", "chocolate", "cream cheese"],
  size: "130G"
},
{
  id: 257,
  name: "Cream Cheese Danish Braid with Berries",
  price: 14.50,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Cream Cheese Danish Braid with Berries.webp",
  description: "Braided Danish filled with cream cheese and berries.",
  fullDescription: "An elegant braided Danish pastry filled with silky cream cheese and mixed berries, offering a balance of sweetness and tang.",
  ingredients: "Flour, butter, cream cheese, berries, sugar, eggs, milk",
  weight: "180g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 41,
  tags: ["berries", "braided", "danish"],
  size: "180G"
},
{
  id: 258,
  name: "Mini Cheese Danish",
  price: 7.90,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Mini Cheese Danish.png",
  description: "Small Danish pastry with creamy cheese filling.",
  fullDescription: "A bite-sized Danish pastry featuring a rich and creamy cheese center, perfect for a light snack or coffee pairing.",
  ingredients: "Flour, butter, cream cheese, sugar, eggs, milk",
  weight: "90g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 33,
  tags: ["mini", "cheese", "danish"],
  size: "90G"
},
{
  id: 259,
  name: "Lemon Raspberry Cream Cheese Danish",
  price: 13.90,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Lemon Raspberry Cream Cheese Danish.webp",
  description: "Danish with lemon, raspberry, and cream cheese.",
  fullDescription: "A bright and refreshing Danish pastry combining tangy lemon, sweet raspberries, and smooth cream cheese in flaky layers.",
  ingredients: "Flour, butter, cream cheese, raspberries, lemon, sugar, eggs",
  weight: "140g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 46,
  tags: ["lemon", "raspberry", "danish"],
  size: "140G"
},
{
  id: 260,
  name: "Almond Mascarpone Danish",
  price: 14.20,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Almond Mascarpone Danish Pastries.jpg",
  description: "Danish pastry with almond and mascarpone filling.",
  fullDescription: "A luxurious Danish pastry filled with creamy mascarpone and topped with toasted almonds for a rich, nutty finish.",
  ingredients: "Flour, butter, mascarpone, almonds, sugar, eggs, milk",
  weight: "150g",
  allergens: "Contains gluten, dairy, eggs, nuts",
  rating: 4.7,
  reviewCount: 38,
  tags: ["almond", "mascarpone", "danish"],
  size: "150G"
},
{
  id: 261,
  name: "Raspberry Cream Cheese Pinwheel",
  price: 11.80,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Raspberry Cream Cheese Pinwheel Pastries.jpg",
  description: "Pinwheel Danish with raspberry and cream cheese.",
  fullDescription: "A visually striking pinwheel Danish pastry filled with tangy raspberry jam and smooth cream cheese, baked to golden perfection.",
  ingredients: "Flour, butter, cream cheese, raspberries, sugar, eggs",
  weight: "125g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 29,
  tags: ["pinwheel", "raspberry", "danish"],
  size: "125G"
},
{
  id: 262,
  name: "Fruit and Cream Cheese Danish",
  price: 13.50,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Fruit and Cream Cheese Breakfast Pastries.jpg",
  description: "Danish pastry topped with fruit and cream cheese.",
  fullDescription: "A breakfast-style Danish pastry topped with seasonal fruits and a creamy cheese base, offering a light yet indulgent bite.",
  ingredients: "Flour, butter, cream cheese, mixed fruits, sugar, eggs",
  weight: "145g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.5,
  reviewCount: 26,
  tags: ["fruit", "breakfast", "danish"],
  size: "145G"
},
{
  id: 263,
  name: "Classic Cheese Danish",
  price: 10.90,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Cheese Danish.jpg",
  description: "Traditional Danish with sweet cheese filling.",
  fullDescription: "A classic Danish pastry filled with lightly sweetened cream cheese, featuring flaky layers and a soft, creamy center.",
  ingredients: "Flour, butter, cream cheese, sugar, eggs, milk",
  weight: "120g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 58,
  tags: ["classic", "cheese", "danish"],
  size: "120G"
},
{
  id: 264,
  name: "Cherry Cream Cheese Danish",
  price: 13.20,
  category: "pastry",
  subcategory: "danish",
  image: "pastries/Danish Pastries/Cherry Cream Cheese Danish.jpg",
  description: "Danish pastry with cherry and cream cheese.",
  fullDescription: "A sweet and tangy Danish pastry filled with cherries and smooth cream cheese, finished with a golden flaky crust.",
  ingredients: "Flour, butter, cream cheese, cherries, sugar, eggs",
  weight: "140g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 44,
  tags: ["cherry", "cream cheese", "danish"],
  size: "140G"
},

{
  id: 265,
  name: "Mini Fruit Tarts",
  price: 9.80,
  category: "pastry",
  subcategory: "tart",
  image: "pastries/Tarts/Mini Fruit Tarts.jpg",
  description: "Mini tart shells filled with cream and fresh fruits.",
  fullDescription: "Delicate mini tart shells filled with smooth vanilla cream and topped with a colorful selection of fresh fruits for a light, refreshing dessert.",
  ingredients: "Flour, butter, cream, sugar, eggs, fresh fruits",
  weight: "90g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 48,
  tags: ["fruit", "mini", "tart"],
  size: "90G"
},
{
  id: 266,
  name: "White Chocolate Raspberry Mini Tarts",
  price: 11.50,
  category: "pastry",
  subcategory: "tart",
  image: "pastries/Tarts/White Chocolate Raspberry Mini Tarts.webp",
  description: "Mini tarts with white chocolate and raspberry.",
  fullDescription: "Elegant mini tarts featuring a creamy white chocolate filling balanced with the tartness of fresh raspberries, finished in a buttery crust.",
  ingredients: "Flour, butter, white chocolate, cream, raspberries, eggs",
  weight: "95g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 36,
  tags: ["white chocolate", "raspberry", "tart"],
  size: "95G"
},
{
  id: 267,
  name: "Vegan Lemon Tart",
  price: 12.90,
  category: "pastry",
  subcategory: "tart",
  image: "pastries/Tarts/Easy Vegan Lemon Tarts.jpg",
  description: "Plant-based lemon tart with a smooth filling.",
  fullDescription: "A bright and zesty vegan lemon tart with a silky smooth filling and crisp crust, delivering refreshing citrus flavor without dairy or eggs.",
  ingredients: "Flour, coconut milk, lemon, sugar, plant-based butter",
  weight: "140g",
  allergens: "Contains gluten",
  rating: 4.6,
  reviewCount: 29,
  tags: ["vegan", "lemon", "tart"],
  size: "140G"
},
{
  id: 268,
  name: "White Chocolate Mousse Tart",
  price: 13.80,
  category: "pastry",
  subcategory: "tart",
  image: "pastries/Tarts/Rich White Chocolate Mousse Tart.webp",
  description: "Creamy white chocolate mousse tart.",
  fullDescription: "A rich and indulgent tart featuring airy white chocolate mousse set in a crisp tart shell, perfect for those who love smooth and creamy desserts.",
  ingredients: "Flour, butter, white chocolate, cream, eggs, sugar",
  weight: "150g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.9,
  reviewCount: 42,
  tags: ["white chocolate", "mousse", "tart"],
  size: "150G"
},

{
  id: 269,
  name: "Mascarpone Berry Puff Pastry",
  price: 13.50,
  category: "pastry",
  subcategory: "puff",
  image: "pastries/Puff Pastry/Mascarpone Puff Pastry.jpg",
  description: "Puff pastry filled with mascarpone and fresh berries.",
  fullDescription: "Light and flaky puff pastry layered with smooth mascarpone cream and topped with fresh mixed berries for a delicate yet indulgent treat.",
  ingredients: "Flour, butter, mascarpone, berries, sugar, eggs",
  weight: "150g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 46,
  tags: ["mascarpone", "berries", "puff pastry"],
  size: "150G"
},
{
  id: 270,
  name: "Chocolate Puff Pastry Roll",
  price: 11.90,
  category: "pastry",
  subcategory: "puff",
  image: "pastries/Puff Pastry/Chocolate Puff Pastry.jpg",
  description: "Flaky puff pastry filled with melted chocolate.",
  fullDescription: "A golden puff pastry roll filled with rich, melted chocolate, offering crisp layers on the outside and a soft, gooey center.",
  ingredients: "Flour, butter, chocolate, sugar, eggs",
  weight: "130g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 39,
  tags: ["chocolate", "puff pastry", "dessert"],
  size: "130G"
},
{
  id: 271,
  name: "Ham, Egg and Cheese Puff Pastry",
  price: 14.90,
  category: "pastry",
  subcategory: "puff",
  image: "pastries/Puff Pastry/Ham Egg and Cheese Puff Pastry.jpg",
  description: "Savory puff pastry with ham, egg, and cheese.",
  fullDescription: "A hearty savory puff pastry filled with fluffy egg, melted cheese, and slices of ham, baked until golden and crisp.",
  ingredients: "Flour, butter, eggs, cheese, ham, milk",
  weight: "180g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 34,
  tags: ["savory", "ham", "puff pastry"],
  size: "180G"
},
{
  id: 272,
  name: "Puff Pastry Cinnamon Rolls",
  price: 12.50,
  category: "pastry",
  subcategory: "puff",
  image: "pastries/Puff Pastry/Puff Pastry Cinnamon Rolls.jpg",
  description: "Cinnamon rolls made with flaky puff pastry.",
  fullDescription: "A creative twist on classic cinnamon rolls using buttery puff pastry, swirled with cinnamon sugar and finished with a sweet glaze.",
  ingredients: "Flour, butter, cinnamon, sugar, milk, eggs",
  weight: "160g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.9,
  reviewCount: 51,
  tags: ["cinnamon", "rolls", "puff pastry"],
  size: "160G"
},

{
  id: 273,
  name: "Chocolate M&M Cookies",
  price: 8.50,
  category: "cookie",
  subcategory: "chocolatechip",
  image: "cookies/Chocolate Chip/Chocolate M&M Cookies.webp",
  description: "Chocolate cookies loaded with colorful M&M chocolates.",
  fullDescription: "Rich and fudgy chocolate cookies packed with crunchy, colorful M&M chocolates, delivering a perfect balance of soft centers and chocolatey bites.",
  ingredients: "Flour, cocoa powder, butter, sugar, eggs, chocolate, M&M candies",
  weight: "110g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 62,
  tags: ["chocolate", "m&m", "cookie"],
  size: "110G"
},
{
  id: 274,
  name: "Triple Chocolate Chunk Cookies",
  price: 9.20,
  category: "cookie",
  subcategory: "chocolatechip",
  image: "cookies/Chocolate Chip/Triple Chocolate Chunk.webp",
  description: "Triple chocolate cookies with rich chocolate chunks.",
  fullDescription: "Decadent cookies made with dark cocoa dough and loaded with milk, dark, and white chocolate chunks for an intense chocolate experience.",
  ingredients: "Flour, cocoa powder, butter, sugar, eggs, dark chocolate, milk chocolate, white chocolate",
  weight: "120g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.9,
  reviewCount: 58,
  tags: ["triple chocolate", "chunk", "cookie"],
  size: "120G"
},
{
  id: 275,
  name: "Classic Chewy Chocolate Chip Cookies",
  price: 7.90,
  category: "cookie",
  subcategory: "chocolatechip",
  image: "cookies/Chocolate Chip/Chewy Chocolate Chip Cookies.jpg",
  description: "Classic chewy cookies with chocolate chips.",
  fullDescription: "Soft and chewy classic chocolate chip cookies baked to golden perfection, filled with melty chocolate chips in every bite.",
  ingredients: "Flour, butter, brown sugar, eggs, chocolate chips, vanilla",
  weight: "105g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 71,
  tags: ["classic", "chewy", "chocolate chip"],
  size: "105G"
},
{
  id: 276,
  name: "Double Chocolate Chip Cookies",
  price: 8.90,
  category: "cookie",
  subcategory: "chocolatechip",
  image: "cookies/Chocolate Chip/Double Chocolate Chip Cookies.jpg",
  description: "Chocolate cookies with extra chocolate chips.",
  fullDescription: "Ultra-rich double chocolate cookies made with cocoa dough and packed with generous chocolate chips for serious chocolate lovers.",
  ingredients: "Flour, cocoa powder, butter, sugar, eggs, chocolate chips",
  weight: "115g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.8,
  reviewCount: 64,
  tags: ["double chocolate", "cookie", "chocolate chip"],
  size: "115G"
},

{
  id: 277,
  name: "Dark Chocolate Almond Butter Cookies",
  price: 8.80,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Dark Chocolate Almond Butter Cookies.jpg",
  description: "Buttery cookies dipped in dark chocolate and almonds.",
  fullDescription: "Classic butter cookies with a crisp texture, half-dipped in rich dark chocolate and topped with crunchy almond pieces for a refined finish.",
  ingredients: "Flour, butter, sugar, eggs, dark chocolate, almonds",
  weight: "100g",
  allergens: "Contains gluten, dairy, eggs, nuts",
  rating: 4.8,
  reviewCount: 46,
  tags: ["butter cookie", "dark chocolate", "almond"],
  size: "100G"
},
{
  id: 278,
  name: "Chocolate Butter Swirl Cookies",
  price: 8.50,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Chocolate Butter Swirl Cookies.jpg",
  description: "Swirled butter cookies with chocolate coating.",
  fullDescription: "Elegant swirl-shaped butter cookies dipped in smooth chocolate, offering a melt-in-the-mouth texture with a rich cocoa finish.",
  ingredients: "Flour, butter, sugar, eggs, cocoa, chocolate",
  weight: "105g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 52,
  tags: ["swirl", "butter cookie", "chocolate"],
  size: "105G"
},
{
  id: 279,
  name: "Vanilla Butter Spritz Cookies",
  price: 7.90,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Vanilla Butter Spritz Cookies.jpg",
  description: "Classic vanilla-flavoured spritz butter cookies.",
  fullDescription: "Traditional spritz butter cookies made with premium butter and vanilla, baked until lightly golden with a tender, crumbly bite.",
  ingredients: "Flour, butter, sugar, eggs, vanilla extract",
  weight: "95g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 41,
  tags: ["spritz", "vanilla", "butter cookie"],
  size: "95G"
},
{
  id: 280,
  name: "Rainbow Drizzle Butter Cookies",
  price: 8.20,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Rainbow Drizzle Butter Cookies.jpg",
  description: "Butter cookies with colorful sugar drizzle.",
  fullDescription: "Soft butter cookies topped with a playful rainbow sugar drizzle, combining classic buttery flavour with a cheerful visual twist.",
  ingredients: "Flour, butter, sugar, eggs, icing sugar",
  weight: "100g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.5,
  reviewCount: 38,
  tags: ["butter cookie", "rainbow", "sweet"],
  size: "100G"
},
{
  id: 281,
  name: "Classic Butter Cookies",
  price: 7.50,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Butter Cookies.webp",
  description: "Traditional crisp and buttery cookies.",
  fullDescription: "Timeless classic butter cookies with a rich aroma and crisp bite, perfect for tea-time or everyday indulgence.",
  ingredients: "Flour, butter, sugar, eggs",
  weight: "100g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 59,
  tags: ["classic", "butter cookie"],
  size: "100G"
},
{
  id: 282,
  name: "Ever Butter Cookies",
  price: 7.80,
  category: "cookie",
  subcategory: "butter",
  image: "cookies/Butter Cookies/Ever Butter Cookies.webp",
  description: "Golden baked butter cookies with smooth texture.",
  fullDescription: "Golden, swirl-shaped butter cookies baked to perfection, offering a delicate crumb and rich buttery flavour in every bite.",
  ingredients: "Flour, butter, sugar, eggs",
  weight: "100g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 44,
  tags: ["butter", "swirl", "cookie"],
  size: "100G"
},

{
  id: 283,
  name: "Chocolate Oatmeal Cookies",
  price: 8.30,
  category: "cookie",
  subcategory: "oatmeal",
  image: "cookies/Oatmeal Cookies/Chocolate Oatmeal Cookies.jpg",
  description: "Oatmeal cookies mixed with rich chocolate.",
  fullDescription: "Hearty oatmeal cookies blended with melted chocolate, offering a chewy texture with a satisfying balance of oats and cocoa richness.",
  ingredients: "Oats, flour, butter, brown sugar, eggs, chocolate",
  weight: "115g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.7,
  reviewCount: 49,
  tags: ["oatmeal", "chocolate", "cookie"],
  size: "115G"
},
{
  id: 284,
  name: "Oatmeal Coconut Cookies",
  price: 8.10,
  category: "cookie",
  subcategory: "oatmeal",
  image: "cookies/Oatmeal Cookies/Oatmeal Coconut Cookies.webp",
  description: "Oatmeal cookies with coconut flakes.",
  fullDescription: "Soft and chewy oatmeal cookies infused with toasted coconut flakes, delivering a lightly sweet, tropical twist in every bite.",
  ingredients: "Oats, flour, butter, sugar, eggs, coconut",
  weight: "110g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 42,
  tags: ["oatmeal", "coconut", "cookie"],
  size: "110G"
},
{
  id: 285,
  name: "Iced Oatmeal Cookies",
  price: 7.90,
  category: "cookie",
  subcategory: "oatmeal",
  image: "cookies/Oatmeal Cookies/Iced Oatmeal Cookies.webp",
  description: "Classic oatmeal cookies topped with icing.",
  fullDescription: "Traditional spiced oatmeal cookies finished with a smooth vanilla icing glaze, combining soft chewiness with a lightly crisp surface.",
  ingredients: "Oats, flour, butter, sugar, eggs, icing sugar",
  weight: "105g",
  allergens: "Contains gluten, dairy, eggs",
  rating: 4.6,
  reviewCount: 51,
  tags: ["iced", "oatmeal", "classic"],
  size: "105G"
},
{
  id: 286,
  name: "Oatmeal Cranberry Walnut Cookies",
  price: 8.70,
  category: "cookie",
  subcategory: "oatmeal",
  image: "cookies/Oatmeal Cookies/Oatmeal Cranberry Walnut Cookies.webp",
  description: "Oatmeal cookies with cranberry and walnuts.",
  fullDescription: "Wholesome oatmeal cookies loaded with tart cranberries and crunchy walnuts, offering a perfect mix of chewy, sweet, and nutty flavours.",
  ingredients: "Oats, flour, butter, brown sugar, eggs, cranberries, walnuts",
  weight: "120g",
  allergens: "Contains gluten, dairy, eggs, nuts",
  rating: 4.8,
  reviewCount: 47,
  tags: ["oatmeal", "cranberry", "walnut"],
  size: "120G"
},




            // Cookie products (existing)
            {
                id: 22,
                name: "Chocolate Chip Cookies",
                price: 8.00,
                category: "cookie",
                subcategory: "chocolatechip",
                image: "https://images.unsplash.com/photo-1499636136210-6f4ee915583e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                description: "Classic cookies with rich chocolate chips",
                fullDescription: "Our classic chocolate chip cookies are soft and chewy.",
                ingredients: "Flour, butter, chocolate chips, sugar, eggs, vanilla",
                weight: "12 cookies per pack",
                allergens: "Contains gluten, dairy, eggs",
                rating: 4.7,
                reviewCount: 33,
                tags: ["classic", "chocolate"],
                size: "12PCS"
            },

            // --------- previously added 5-inch user cakes (23-29) ----------
            {
                id: 23,
                name: "BABY PENGUINSSS",
                price: 140.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Baby_Penguinsss.jpg",
                description: "Adorable blue-themed cake featuring a cute penguin ready to celebrate.",
                fullDescription: "This charming 5-inch celebration cake features a cheerful penguin wearing a tiny birthday hat, surrounded by stars, sprinkles, and a dreamy blue frosting swirl. Soft, airy, and delicately sweet, it's perfect for birthdays, surprises, or anyone who loves cute themed cakes.",
                ingredients: "Premium flour, sugar, eggs, milk, butter, light whipped cream",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.7,
                reviewCount: 18,
                tags: ["5inch", "cute", "kids", "popular"],
                size: "5 INCH"
            },
            {
                id: 24,
                name: "BEAR CANDLE",
                price: 78.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Bear_Candle.jpg",
                description: "Soft pastel cake topped with a sweet teddy and playful cream piping.",
                fullDescription: "A lovely pastel-style 5-inch cake dressed in soft pink tones, decorated with rounded cream swirls and a tiny teddy holding a heart. Light, creamy, and beautifully piped, this cake brings a gentle sweetness to baby showers, birthdays, or heartfelt moments.",
                ingredients: "Flour, sugar, eggs, butter, whipped cream, food-safe fondant",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.6,
                reviewCount: 14,
                tags: ["5inch", "pastel", "cute"],
                size: "5 INCH"
            },
            {
                id: 25,
                name: "BLACK AND WHITE",
                price: 78.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Black_and_White.jpg",
                description: "Elegant monochrome cake featuring a stylish black bear and luxe decorations.",
                fullDescription: "This 5-inch modern cake combines cream white icing with black geometric accents, metallic elements, and a deluxe bear topper. Perfect for those who love bold aesthetics, minimal contrast designs, and a touch of luxury for adult birthdays or refined gatherings.",
                ingredients: "Flour, sugar, eggs, butter, fondant accents, edible metallic decor",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.8,
                reviewCount: 22,
                tags: ["5inch", "elegant", "minimal"],
                size: "5 INCH"
            },
            {
                id: 26,
                name: "BLAZING LOVE",
                price: 140.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Blazing_Love.jpg",
                description: "Romantic heart-themed cake with a cute teddy surrounded by red hearts.",
                fullDescription: "Wrapped in red chocolate panels and topped with a teddy hugging a big heart, this 5-inch cake is designed for love-filled surprises. Charming heart decorations and a soft cream center make it ideal for anniversaries, Valentine’s Day, or romantic surprises.",
                ingredients: "Flour, sugar, eggs, butter, cream, chocolate panels",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.6,
                reviewCount: 16,
                tags: ["5inch", "romantic", "valentine"],
                size: "5 INCH"
            },
            {
                id: 27,
                name: "Peach Capybara Cake",
                price: 98.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Capybara.jpg",
                description: "Cute capybara cake topped with a tiny peach for an adorable finish.",
                fullDescription: "This 5-inch cake captures the gentle and silly charm of a capybara, complete with expressive face details and a peach on its head. Soft, fluffy and irresistibly cute — perfect for animal lovers and casual celebrations.",
                ingredients: "Flour, sugar, eggs, butter, cream, fondant details",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.5,
                reviewCount: 12,
                tags: ["5inch", "animal", "cute"],
                size: "5 INCH"
            },
            {
                id: 28,
                name: "CASTLE RABBIT",
                price: 140.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Castle_Rabbit.jpg",
                description: "Sweet bunny cake sitting in a swirl of rainbow marshmallows and hearts.",
                fullDescription: "Dreamy and colorful, this 5-inch cake features an adorable bunny surrounded by marshmallow twists and pastel hearts. Light, cheerful, and perfect for kids or anyone who loves charming pastel aesthetics.",
                ingredients: "Flour, sugar, eggs, butter, whipped cream, marshmallow candy",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.7,
                reviewCount: 20,
                tags: ["5inch", "pastel", "kids"],
                size: "5 INCH"
            },
            {
                id: 29,
                name: "CHEERS BEER MUG CAKE",
                price: 68.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Cheers.jpg",
                description: "Fun beer-themed cream cake with bubbly foam decoration.",
                fullDescription: "This cheerful 5-inch celebration cake is decorated with mini beer mugs and creamy foam accents. It’s the perfect choice for birthdays, gatherings, or a funny surprise for someone who loves beer-themed designs.",
                ingredients: "Flour, sugar, eggs, butter, cream, fondant decorations",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.4,
                reviewCount: 11,
                tags: ["5inch", "fun", "party"],
                size: "5 INCH"
            },

            // ------------------ NEW ITEMS (id 30 - 38) ------------------
            {
                id: 30,
                name: "CHOCO ERAL DRIP",
                price: 98.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Choc_Eral.jpg",
                description: "Tall chocolate drip cake dusted with cocoa, topped with fresh strawberries and chocolate curls.",
                fullDescription: "A premium 5-inch tall chocolate cake coated in fine cocoa powder and finished with a glossy dark chocolate drip. The top is elegantly decorated with fresh strawberries, chocolate curls and a cinnamon accent for aroma. Moist, rich and velvety — ideal for chocolate lovers and special moments.",
                ingredients: "Flour, sugar, cocoa powder, eggs, milk, butter, chocolate, fresh strawberries",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.7,
                reviewCount: 21,
                tags: ["5inch", "chocolate", "premium"],
                size: "5 INCH"
            },
            
            {
                id: 32,
                name: "CHOCOLATE DELIGHT FEATHER",
                price: 138.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Chocolate_Delight.jpg",
                description: "Luxurious chocolate drip cake topped with Ferrero, macaron and dramatic chocolate feather.",
                fullDescription: "A decadent 5-inch chocolate cake with a rich dark chocolate drip and a dramatic chocolate feather for height. Topped with Ferrero Rocher, macaron, mini Oreos and crunchy pearls, this cake offers layers of texture and deep chocolate flavour — a striking centrepiece for celebrations.",
                ingredients: "Flour, sugar, cocoa, butter, cream, Ferrero Rocher, macaron, Oreo, chocolate pearls",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy, nuts",
                rating: 4.8,
                reviewCount: 25,
                tags: ["5inch", "luxury", "chocolate"],
                size: "5 INCH"
            },
            {
                id: 33,
                name: "CHOCOLATE DREAMS TREATS",
                price: 138.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Chocolate_Dreams.jpg",
                description: "Cream-gradient drip cake loaded with KitKat, Ferrero, pretzel and chocolate bites.",
                fullDescription: "This playful 5-inch celebration cake features a smooth white-to-brown cream gradient with a dark chocolate drip. Generously topped with KitKat bars, Ferrero Rocher, pretzels, waffle pieces and chocolate accents, it delivers delightful contrasts of crunch and cream in every slice.",
                ingredients: "Flour, sugar, eggs, butter, cream, KitKat, Ferrero Rocher, pretzels, waffle pieces",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy, nuts",
                rating: 4.7,
                reviewCount: 19,
                tags: ["5inch", "party", "assorted-treats"],
                size: "5 INCH"
            },
            {
                id: 34,
                name: "COCOREO PARADISE",
                price: 128.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Cocoreo_Paradise.jpg",
                description: "Cookies & cream cake finished with chocolate drip, Ferrero and Oreo accents.",
                fullDescription: "A delightful 5-inch cookies & cream cake finished with a rich chocolate drip and generous toppings of Ferrero Rocher, Oreo cookies and chocolate bars. Creamy, crunchy and indulgent — a favourite for Oreo lovers and anyone craving a textured chocolate treat.",
                ingredients: "Cream, Oreo crumbs, flour, eggs, sugar, chocolate, Ferrero Rocher",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy, nuts",
                rating: 4.8,
                reviewCount: 17,
                tags: ["5inch", "cookies-and-cream", "popular"],
                size: "5 INCH"
            },
            {
                id: 35,
                name: "COOKIES MONSTER FUN",
                price: 108.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Cookies_Monster.jpg",
                description: "Playful Oreo monster cake topped with cute googly-eyed cookies.",
                fullDescription: "This adorable 5-inch cookies & cream cake is decorated with playful Oreo 'monsters' featuring edible googly eyes. Fun, whimsical and delicious — perfect for children's parties or themed celebrations that need a smile with every slice.",
                ingredients: "Cream, Oreo, flour, sugar, eggs, chocolate",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.6,
                reviewCount: 20,
                tags: ["5inch", "cute", "kids"],
                size: "5 INCH"
            },
            {
                id: 36,
                name: "CUTIE CANDLE STRAWBERRY",
                price: 88.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Cutiee_Candlee.jpg",
                description: "Sweet pastel mini cake with fresh strawberries and a tall birthday candle.",
                fullDescription: "A charming 5-inch pastel cake featuring a smiling face design, soft cream layers and fresh strawberries. Topped with a tall striped candle, this cake brings a warm and playful vibe to small celebrations and intimate birthdays.",
                ingredients: "Flour, sugar, eggs, cream, fresh strawberries",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.5,
                reviewCount: 14,
                tags: ["5inch", "pastel", "cute"],
                size: "5 INCH"
            },
            {
                id: 37,
                name: "CUTIE POCHACCO BEACH",
                price: 140.00,
                category: "cake",
                subcategory: "5 inch",
                image: "cake/Cutiee_Pochacco.jpg",
                description: "Adorable Pochacco-themed beach cake with float ring and mini umbrella.",
                fullDescription: "This playful 5-inch cake showcases Pochacco relaxing in a float ring surrounded by tiny waves, pearls and a colourful umbrella. Bright, cheerful and perfect for kids or character-themed parties, it’s as delightful to look at as it is to taste.",
                ingredients: "Flour, sugar, eggs, cream, fondant figure, decorative sprinkles",
                weight: "5-inch (Serves 4-6 people)",
                allergens: "Contains: gluten, eggs, dairy",
                rating: 4.7,
                reviewCount: 22,
                tags: ["5inch", "character", "cute"],
                size: "5 INCH"
            },
            {
    id: 38,
    name: "DOUBLE DELIGHT MINI TIER",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Double_Delight.jpg",
    description: "Two-tier style mini cake with chocolate drip, fresh strawberries and assorted chocolates.",
    fullDescription: "A premium 5-inch mini-tier cake featuring a peach-to-chocolate cream gradient and rich dark chocolate drip. Decorated with fresh strawberries, chocolate kisses and Ferrero accents, this cake is elegant, flavourful and perfect for special celebrations or gifting.",
    ingredients: "Flour, sugar, eggs, butter, chocolate, strawberries, assorted chocolates",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts",
    rating: 4.8,
    reviewCount: 19,
    tags: ["5inch", "tier", "premium"],
    size: "5 INCH"
},
{
    id: 39,
    name: "DOUBLE MATCHA DELIGHT",
    price: 118.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Double_Matcha.jpg",
    description: "Light and refreshing double-layer matcha cake topped with fresh fruits.",
    fullDescription: "DOUBLE MATCHA DELIGHT features soft sponge layers infused with premium Japanese matcha, covered in smooth matcha cream. Decorated with strawberries, blueberries and dried citrus, this cake delivers a balanced sweetness and a fragrant earthy matcha aroma — perfect for matcha lovers seeking a refreshing treat.",
    ingredients: "Flour, sugar, eggs, milk, unsalted butter, Japanese matcha powder, fresh cream, strawberries, blueberries, dried citrus slices",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 12,
    tags: ["5inch", "matcha", "fresh"],
    size: "5 INCH"
},
{
    id: 40,
    name: "DOUBLE OREO TEMPTATION",
    price: 128.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Double_Oreo.jpg",
    description: "A rich double-layer Oreo cake with creamy cookies-and-cream frosting and chocolate drip.",
    fullDescription: "DOUBLE OREO TEMPTATION combines crushed Oreo cream filling with soft sponge layers, topped with a decadent chocolate drip and whole Oreo cookies. It offers the perfect mix of creamy, crunchy and chocolaty textures — a dream come true for Oreo fans.",
    ingredients: "Flour, sugar, eggs, milk, unsalted butter, fresh cream, Oreo cookies, dark chocolate",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 20,
    tags: ["5inch", "oreo", "chocolate"],
    size: "5 INCH"
},
{
    id: 41,
    name: "EARTH MERMAID",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Earthh_Mermaid.jpg",
    description: "A charming ocean-themed cake topped with a mermaid tail and colorful sea decorations.",
    fullDescription: "EARTH MERMAID brings ocean fantasy to life with its wave-textured blue frosting, fondant mermaid tail, shells and floral decorations. Cute, vibrant and full of character, this cake is ideal for birthdays, children’s parties, or anyone who loves whimsical themes.",
    ingredients: "Flour, sugar, eggs, milk, unsalted butter, fresh cream, fondant decorations, edible coloring, sugar pearls",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 14,
    tags: ["5inch", "mermaid", "fondant"],
    size: "5 INCH"
},
{
    id: 42,
    name: "ENJOY DREAM CHOCOLATE",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Enjoy_Dream.jpg",
    description: "Elegant chocolate drip cake topped with a mini whiskey bottle and assorted chocolate pieces.",
    fullDescription: "ENJOY DREAM CHOCOLATE is crafted with a rich chocolate frosting and glossy drip, finished with assorted chocolate bars, blueberries and a decorative whiskey bottle. With its bold and refined presentation, it’s a perfect choice for celebrations and chocolate lovers who enjoy a mature, premium look.",
    ingredients: "Flour, sugar, eggs, milk, unsalted butter, fresh cream, dark chocolate, assorted chocolate pieces, blueberries",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, may contain nuts",
    rating: 4.9,
    reviewCount: 18,
    tags: ["5inch", "chocolate", "whiskey-theme"],
    size: "5 INCH"
},
{
    id: 43,
    name: "ENTWINE CHOCOLATE BARREL",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Entwine_Choc.jpg",
    description: "A premium chocolate-loaded cake with layered textures and whiskey-inspired decoration.",
    fullDescription: "ENTWINE CHOCOLATE BARREL is packed with luxurious chocolate elements — chocolate drip, bars, truffles and cookies. Finished with a whiskey bottle decoration, this cake delivers deep chocolate flavor and an elegant celebration look.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate, assorted chocolate bars, Ferrero Rocher, Oreo cookies",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts",
    rating: 4.8,
    reviewCount: 22,
    tags: ["5inch", "chocolate", "luxury"],
    size: "5 INCH"
},
{
    id: 44,
    name: "EXQUISITE GIRL",
    price: 158.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Exquisite_Girl.jpg",
    description: "A cute and stylish character cake featuring vibrant fondant decorations.",
    fullDescription: "EXQUISITE GIRL showcases an adorable character made with detailed fondant elements — braids, glasses, headband and expressive features. Bright, charming and full of personality, it’s a perfect cake for girls’ birthdays or anyone who loves character-themed designs.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant, edible coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 15,
    tags: ["5inch", "fondant", "character"],
    size: "5 INCH"
},
{
    id: 45,
    name: "BEARY GIFT DELIGHT",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Gift_Lotsoo.jpg",
    description: "A playful two-tier mini cake topped with a cute bear, strawberries and decorative sweets.",
    fullDescription: "BEARY GIFT DELIGHT features a charming bear topper, layered cream pipings and assorted chocolate and fruit decorations. With bright pink accents and playful shapes, this cake is perfect for anniversaries, surprise gifts or any joyful celebration.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, strawberries, assorted chocolates",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 11,
    tags: ["5inch", "character", "gift"],
    size: "5 INCH"
},
{
    id: 46,
    name: "GREEN MATCHA KITWRAP",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Green_Matcha.jpg",
    description: "Elegant matcha cake wrapped with green chocolate sticks and topped with macarons and strawberries.",
    fullDescription: "GREEN MATCHA KITWRAP is a refined matcha-flavored cake encircled with matcha chocolate fingers and finished with powdered strawberries and macarons. The matcha crumb and smooth cream create a delicate balance of umami and sweetness.",
    ingredients: "Flour, sugar, eggs, milk, unsalted butter, Japanese matcha powder, fresh cream, matcha chocolate sticks, macarons, strawberries",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts (in macarons)",
    rating: 4.8,
    reviewCount: 16,
    tags: ["5inch", "matcha", "elegant"],
    size: "5 INCH"
},
{
    id: 47,
    name: "HONEY BUBBLE LATTE",
    price: 118.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/H_Tea.jpg",
    description: "Trendy bubble-tea inspired cake with creamy swirl topping and chocolate boba accents.",
    fullDescription: "HONEY BUBBLE LATTE captures the charm of a milk tea drink with its soft brown ombré frosting, swirled cream top and chocolate 'boba' decorations around the base. A fun, modern choice for casual birthdays and themed parties.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, chocolate pearls, espresso or tea flavoring, macaron",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 10,
    tags: ["5inch", "bubbletea", "trendy"],
    size: "5 INCH"
},
{
    id: 48,
    name: "ICE CREAM SCOOP FEST",
    price: 128.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Ice_Scream.jpg",
    description: "Playful ice-cream themed cake topped with colorful scoops, chocolate drips and fresh strawberries.",
    fullDescription: "ICE CREAM SCOOP FEST is decorated to look like an ice-cream sundae with piped cream scoops, chocolate drips and sweet toppings. Bright, whimsical and full of texture, it’s great for children’s parties or anyone who loves playful dessert styling.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, food coloring, chocolate topping, strawberries",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 13,
    tags: ["5inch", "icecream", "party"],
    size: "5 INCH"
},
{
    id: 49,
    name: "KITKAT CELEBRATION",
    price: 158.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Kitcake_Cross.jpg",
    description: "Classic KitKat-wrapped cake with rich chocolate rosette top and festive ribbon.",
    fullDescription: "KITKAT CELEBRATION is surrounded by chocolate sticks and topped with a glossy chocolate rosette centre. A red ribbon finishes the presentation, making it an elegant yet fun choice for birthdays and special occasions.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, KitKat bars, dark chocolate, decorative ribbon (non-edible)",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, may contain nuts",
    rating: 4.9,
    reviewCount: 21,
    tags: ["5inch", "kitkat", "celebration"],
    size: "5 INCH"
},
{
    id: 50,
    name: "MUG DRIP TREASURE",
    price: 128.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Lets_Beerrr.jpg",
    description: "Creative mug-shaped drip cake with gold coin accents and chocolate toppers.",
    fullDescription: "MUG DRIP TREASURE mimics a frothy mug with dripping icing and gold-foil chocolate coins. Finished with cookies and a small topper, this novelty cake is perfect for themed parties, groomsmen gifts or playful celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, white chocolate drip, chocolate coins, Oreos",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 9,
    tags: ["5inch", "novelty", "mug"],
    size: "5 INCH"
},
{
    id: 51,
    name: "CHOCOLATE MAGNUM RING",
    price: 168.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Limited_Edition.jpg",
    description: "Luxurious chocolate ring cake wrapped in premium bars and topped with glossy ganache.",
    fullDescription: "CHOCOLATE MAGNUM RING is a decadent centerpiece wrapped with chocolate bars and finished with a rich ganache top. The red ribbon and glossy surface give it a premium feel — ideal for anniversaries or deluxe gifting.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, milk chocolate bars, dark ganache",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy, may contain nuts",
    rating: 4.9,
    reviewCount: 24,
    tags: ["5inch", "chocolate", "premium"],
    size: "5 INCH"
},
{
    id: 52,
    name: "CHOCOLATE DRIP DELUXE",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Magnum_Chocolate.jpg",
    description: "Decadent chocolate drip cake topped with macaron, pretzel and fruit accents.",
    fullDescription: "CHOCOLATE DRIP DELUXE features a smooth cream base with dramatic chocolate drip and an assortment of premium toppings — macarons, pretzels, berries and a mini chocolate bar. The layered textures and rich flavors make it a standout choice for chocolate lovers.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate drip, macarons, pretzel, fresh strawberry, Oreo",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts",
    rating: 4.8,
    reviewCount: 17,
    tags: ["5inch", "drip", "chocolate"],
    size: "5 INCH"
},
{
    id: 53,
    name: "MANGO ROYALE",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Mango_Royale.jpg",
    description: "Elegant mango drip cake crowned with a glossy mango sphere and dried floral accents.",
    fullDescription: "MANGO ROYALE features a smooth cream base with vibrant mango drip, topped with a decorative glossy mango sphere and delicate floral touches. Light, fruity and refined — perfect for summer celebrations or an elegant dessert centrepiece.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, mango puree, gelatin (for sphere), edible flowers",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 10,
    tags: ["5inch", "mango", "elegant"],
    size: "5 INCH"
},
{
    id: 54,
    name: "MERMAID MEMORY (AQUA)",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Mermaid_Memory.jpg",
    description: "Ocean-inspired mermaid cake with pastel aquamarine frosting and fondant tail accents.",
    fullDescription: "MERMAID MEMORY (AQUA) brings seaside fantasy with layered aquamarine frosting, shell and mermaid-tail fondant decorations, and shimmering edible accents. A whimsical choice for birthdays and mermaid-themed parties.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, edible glitter, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 13,
    tags: ["5inch", "mermaid", "fondant"],
    size: "5 INCH"
},
{
    id: 55,
    name: "MERMAID MEMORY (PINK)",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Mermaid_Story.jpg",
    description: "Pink ombré mermaid cake with seashells, pearls and a delicate mermaid-tail topper.",
    fullDescription: "MERMAID MEMORY (PINK) features a soft pink-to-rose ombré frosting, pearl embellishments and a pastel mermaid tail. Cute, dreamy and perfect for pastel-themed birthdays or baby showers.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, edible pearls, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 11,
    tags: ["5inch", "mermaid", "pastel"],
    size: "5 INCH"
},
{
    id: 56,
    name: "MOCHA LOVER TIRAMISU",
    price: 128.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Mocha_Lover.jpg",
    description: "Layered mocha tiramisu-style cake finished with cocoa-dusted cream and a birthday topper.",
    fullDescription: "MOCHA LOVER TIRAMISU stacks coffee-soaked sponge with silky mascarpone-style cream, decorated with cocoa dusting and creamy swirls. Rich coffee aroma with balanced sweetness — ideal for coffee fans and intimate celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, mascarpone or cream cheese mix, espresso, cocoa powder",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 14,
    tags: ["5inch", "mocha", "tiramisu"],
    size: "5 INCH"
},
{
    id: 57,
    name: "MONBEAR ROSETTE",
    price: 158.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Monbear.jpg",
    description: "Elegant rosette cake in chocolate ombré tones crowned with a cute bear topper and chocolate-dipped strawberries.",
    fullDescription: "MONBEAR ROSETTE presents layered piped rosettes in gradient chocolate hues, finished with fresh chocolate-dipped strawberries and an adorable bear topper — a stylish and charming pick for special birthdays.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, chocolate, strawberries, fondant topper",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.9,
    reviewCount: 18,
    tags: ["5inch", "rosette", "character"],
    size: "5 INCH"
},
{
    id: 58,
    name: "OMMA SWEET HEART",
    price: 128.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Omma.jpg",
    description: "Adorable drip mug-inspired cake with gold coins and cookie accents.",
    fullDescription: "OMMA SWEET HEART mimics a frothy mug with white drip and gold-foil chocolate coins, finished with Oreos and macarons. A playful novelty cake perfect for casual celebrations and themed giftings.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, white chocolate drip, chocolate coins, Oreos, macarons",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 9,
    tags: ["5inch", "novelty", "mug"],
    size: "5 INCH"
},
{
    id: 59,
    name: "PARTY BEAR",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Party_Bear.jpg",
    description: "Cute bear-themed cake decorated with piped flowers and a playful character topper.",
    fullDescription: "PARTY BEAR features pastel piped flowers encircling a cheerful bear figure. Bright, whimsical and filled with personality — a delightful choice for kids’ birthdays and family celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant topper, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 12,
    tags: ["5inch", "character", "kids"],
    size: "5 INCH"
},
{
    id: 60,
    name: "PENGUIN BABY BLUE",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Penguin_Baby_Blue.jpg",
    description: "Adorable penguin-themed cake with blue accents and playful pocket detail.",
    fullDescription: "PENGUIN BABY BLUE is a charming character cake with crisp fondant details and soft blue frosting. Cute pocket motif and simple, clean lines make it perfect for baby showers and children’s birthdays.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 15,
    tags: ["5inch", "penguin", "character"],
    size: "5 INCH"
},
{
    id: 61,
    name: "PENGUIN BABY PINK",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Penguin_Baby_Pink.jpg",
    description: "Cute penguin-themed cake with soft pink overalls and a tiny bear in the pocket.",
    fullDescription: "PENGUIN BABY PINK features a charming penguin character dressed in pastel pink overalls, complete with a mini bear tucked in its pocket. Soft colors and clean fondant details make it perfect for children’s birthdays and baby celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant decorations, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 15,
    tags: ["5inch", "penguin", "character"],
    size: "5 INCH"
},
{
    id: 62,
    name: "ROYAL CHOCOLATE GOLD",
    price: 158.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Royal_Chocolate.jpg",
    description: "Elegant dark chocolate cake topped with edible gold bars and chocolate drip.",
    fullDescription: "ROYAL CHOCOLATE GOLD showcases deep chocolate richness paired with luxurious edible gold bars. Smooth chocolate drip and gold accents make it the perfect premium centerpiece for celebrations and gifting.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, dark chocolate, edible gold coating",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.9,
    reviewCount: 18,
    tags: ["5inch", "chocolate", "luxury"],
    size: "5 INCH"
},
{
    id: 63,
    name: "STARRY NIGHT BEAR",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Starry_Night.jpg",
    description: "Galaxy-themed buttercream cake with rosettes, Oreo pieces and a mini bear topper.",
    fullDescription: "STARRY NIGHT BEAR features a galaxy-inspired frosting blend decorated with piped rosettes, Oreo cookies and a sleepy bear topper. Dreamy, whimsical and perfect for night-sky lovers.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, Oreo cookies, fondant topper, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 14,
    tags: ["5inch", "galaxy", "oreo", "character"],
    size: "5 INCH"
},
{
    id: 64,
    name: "SUNFLOWER BLOOM",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Sunflower.jpg",
    description: "Handcrafted sunflower-themed cake with detailed yellow petals and textured centre.",
    fullDescription: "SUNFLOWER BLOOM features a beautifully piped sunflower top with layered petals and a textured centre. Clean green and white sides complete this elegant, cheerful design. Ideal for birthdays, mothers' day or floral-theme celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, food coloring, chocolate wafer center",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 16,
    tags: ["5inch", "sunflower", "floral"],
    size: "5 INCH"
},
{
    id: 65,
    name: "SUNSHINE GARDEN",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Sunshine.jpg",
    description: "Bright and cheerful sunflower cake decorated with multiple piped flowers.",
    fullDescription: "SUNSHINE GARDEN brings warm, sunny happiness with its multiple hand-piped sunflower designs and soft yellow frosting. Simple, elegant and uplifting — great for birthdays and appreciation occasions.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 13,
    tags: ["5inch", "sunflower", "bright"],
    size: "5 INCH"
},
{
    id: 66,
    name: "SWEET CASTLE DREAM",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Sweet_Castle.jpg",
    description: "Romantic strawberry-and-macaron cake with pink drip and a whimsical ice-cream cone topper.",
    fullDescription: "SWEET CASTLE DREAM is decorated with macarons, berries and a fantasy-style ice-cream cone swirl. Pink drip and heart-shaped accents make it a lovely choice for birthdays, anniversaries or sweet surprise gifts.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, macarons, strawberries, blueberries, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, nuts (in macarons)",
    rating: 4.8,
    reviewCount: 17,
    tags: ["5inch", "macaron", "romantic"],
    size: "5 INCH"
},
{
    id: 67,
    name: "SWEET LIFE ICE CREAM",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Sweet_Life.jpg",
    description: "Cute ice-cream themed cake with cone decorations and pink whipped-cream swirls.",
    fullDescription: "SWEET LIFE ICE CREAM features multiple piped soft-serve ice-cream swirls and cone designs around the cake. Playful, bright and full of fun textures — perfect for kids and pastel-themed celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, wafer cones, food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.6,
    reviewCount: 12,
    tags: ["5inch", "icecream", "cute"],
    size: "5 INCH"
},
{
    id: 69,
    name: "SWEETY BERRY DRIP",
    price: 138.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Sweety_Berry.jpg",
    description: "Fresh strawberry cake with soft pink cream and a smooth white drip finish.",
    fullDescription: "SWEETY BERRY DRIP features a light strawberry-infused cream exterior paired with a smooth white chocolate drip. Topped generously with fresh strawberries, this cake offers a refreshing fruity sweetness — perfect for berry lovers and elegant celebrations.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, strawberries, white chocolate, strawberry puree",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 16,
    tags: ["5inch", "strawberry", "fresh"],
    size: "5 INCH"
},
{
    id: 70,
    name: "TYCOON RICH MAN CAKE",
    price: 158.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Tycoon.jpg",
    description: "Playful rich-man themed fondant cake holding money and wearing sunglasses.",
    fullDescription: "TYCOON RICH MAN CAKE delivers a humorous and stylish fondant character holding cash, paired with bold sunglasses and iconic detailing. Perfect for fun birthdays, playful surprises and celebrations for the ‘rich man’ in your life.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, fondant, edible food coloring",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.7,
    reviewCount: 12,
    tags: ["5inch", "fondant", "character"],
    size: "5 INCH"
},
{
    id: 71,
    name: "WAITING FOR U MATCHA",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/Waiting_For_U.jpg",
    description: "Adorable matcha drip cake topped with a cute bear and mini cash decorations.",
    fullDescription: "WAITING FOR U MATCHA combines soft vanilla cream with a gentle matcha drip, topped with a cute bear figure resting on fluffy cream puffs and mini money notes. Sweet, charming and perfect for birthday surprises.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, matcha powder, fondant topper",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy",
    rating: 4.8,
    reviewCount: 14,
    tags: ["5inch", "matcha", "cute"],
    size: "5 INCH"
},
{
    id: 72,
    name: "WHITE KINDER DELIGHT",
    price: 148.00,
    category: "cake",
    subcategory: "5 inch",
    image: "cake/White_Kinder.jpg",
    description: "Kinder-inspired cake with white drip, chocolate bars and creamy swirl topping.",
    fullDescription: "WHITE KINDER DELIGHT features a velvety white drip paired with layers of whipped cream swirls, topped with Kinder-style chocolate bars. The perfect balance of creamy, milky and chocolatey — ideal for chocolate lovers.",
    ingredients: "Flour, sugar, eggs, milk, butter, fresh cream, white chocolate, Kinder-style chocolate bars",
    weight: "5-inch (Serves 4-6 people)",
    allergens: "Contains: gluten, eggs, dairy, soy",
    rating: 4.9,
    reviewCount: 18,
    tags: ["5inch", "chocolate", "kinder"],
    size: "5 INCH"
}




            // end products
        ];

        // Shopping cart
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        let favorites = JSON.parse(localStorage.getItem('bakeryFavorites')) || [];
        let recentlyViewed = JSON.parse(localStorage.getItem('bakeryRecentlyViewed')) || [];

        // DOM elements
        const productsGrid = document.getElementById('productsGrid');
        const cartIcon = document.getElementById('cartIcon');
        const cartCount = document.querySelector('.cart-count');
        const activeCategory = document.getElementById('activeCategory');
        const resultsInfo = document.getElementById('resultsInfo');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const sortSelect = document.getElementById('sortSelect');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const quickViewModal = document.getElementById('quickViewModal');
        const quickViewContent = document.getElementById('quickViewContent');
        const backToTop = document.getElementById('backToTop');
        const toast = document.getElementById('toast');
        const recentlyViewedSection = document.getElementById('recentlyViewed');
        const recentProductsContainer = document.getElementById('recentProducts');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const pageIndicator = document.getElementById('pageIndicator');

        // Current filter state
        let currentCategory = 'cake'; // Default to cake
        let currentSubCategory = 'all';
        let currentSearch = '';
        let currentSort = 'name';
        let currentPage = 1;
        const productsPerPage = 9;

        // Initialize page
        function initPage() {
            renderProducts();
            updateCartCount();
            loadRecentlyViewed();
            setupEventListeners();
        }

        // Render products with pagination (prev/next)
        function renderProducts() {
            productsGrid.innerHTML = '';
            loadingSpinner.style.display = 'block';

            setTimeout(() => {
                let filteredProducts = filterProducts();

                // Sort products
                filteredProducts = sortProducts(filteredProducts);

                // Pagination maths
                const total = filteredProducts.length;
                const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
                if (currentPage > maxPage) currentPage = maxPage;
                if (currentPage < 1) currentPage = 1;
                const startIndex = (currentPage - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
                const productsToShow = filteredProducts.slice(startIndex, endIndex);

                // Render
                if (productsToShow.length === 0) {
                    productsGrid.innerHTML = '<div class="no-products">No products found matching your criteria.</div>';
                } else {
                    productsToShow.forEach(product => {
                        productsGrid.innerHTML += createProductCard(product);
                    });
                }

                // Update results info and pagination buttons
                updateResultsInfo(total);
                pageIndicator.textContent = `Page ${currentPage} / ${maxPage}`;
                prevPageBtn.disabled = (currentPage <= 1);
                nextPageBtn.disabled = (currentPage >= maxPage);

                loadingSpinner.style.display = 'none';
                setupProductEventListeners();
            }, 150);
        }

        // Filter products based on current criteria
        function filterProducts() {
            return products.filter(product => {
                // Category filter
                if (product.category !== currentCategory) return false;

                // Subcategory filter
                if (currentSubCategory !== 'all' && product.subcategory !== currentSubCategory) return false;

                // Search filter
                if (currentSearch && !(product.name.toLowerCase().includes(currentSearch.toLowerCase()) ||
                    (product.description && product.description.toLowerCase().includes(currentSearch.toLowerCase())))) return false;

                return true;
            });
        }

        // Sort products
        function sortProducts(productsList) {
            switch(currentSort) {
                case 'price-low':
                    return [...productsList].sort((a, b) => a.price - b.price);
                case 'price-high':
                    return [...productsList].sort((a, b) => b.price - a.price);
                case 'rating':
                    return [...productsList].sort((a, b) => (b.rating || 0) - (a.rating || 0));
                case 'popular':
                    return [...productsList].sort((a, b) => (b.reviewCount || 0) - (a.reviewCount || 0));
                default:
                    return [...productsList].sort((a, b) => a.name.localeCompare(b.name));
            }
        }

        // Create product card HTML (string)
        function createProductCard(product) {
            const isFavorite = favorites.includes(product.id);
            const badge = (product.tags && product.tags.includes('popular')) ? 'popular' :
                          (product.tags && product.tags.includes('new')) ? 'new' : '';
            const ratingFloor = Math.floor(product.rating || 0);
            const stars = '★'.repeat(ratingFloor) + '☆'.repeat(Math.max(0, 5 - ratingFloor));
            // ensure description safe
            const desc = product.description ? product.description : '';
            return `
                <div class="product-card" data-id="${product.id}">
                    ${badge ? `<div class="product-badge ${badge}">${badge === 'popular' ? 'Popular' : 'New'}</div>` : ''}
                    <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${product.id}">${isFavorite ? '❤️' : '🤍'}</button>
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-price">RM ${product.price.toFixed(2)}</p>
                        <p class="product-size">${product.size || ''}</p>
                        <div class="product-rating"><span class="stars">${stars}</span><span>${product.rating || ''}</span><span class="rating-count">(${product.reviewCount || 0})</span></div>
                        <p class="product-description">${desc}</p>
                        <button class="view-details-btn" data-id="${product.id}">View Details</button>
                    </div>
                </div>
            `;
        }

        // Update results information
        function updateResultsInfo(totalProducts) {
            const showingStart = Math.min((currentPage - 1) * productsPerPage + 1, totalProducts);
            const showingEnd = Math.min(currentPage * productsPerPage, totalProducts);
            let infoText = `Showing ${totalProducts === 0 ? 0 : showingStart}-${showingEnd} of ${totalProducts} products`;
            if (currentSearch) infoText += ` for "${currentSearch}"`;
            resultsInfo.textContent = infoText;
            // update active category label
            updateActiveCategory();
        }

        // Update active category display text
        function updateActiveCategory() {
            const categoryNames = {'bread':'Bread','cake':'Cakes','pastry':'Pastries','cookie':'Cookies'};
            const subNames = {'all':`All ${categoryNames[currentCategory]}`,'5 inch':'5 inch Cake','cheese':'Cheese Flavour','chocolate':'Chocolate & Coffee','mini':'Cute Mini Cake','durian':'Durian Series','festival':'Festival','fondant':'Fondant Cake Design','fresh-cream':'Fresh Cream Cake','full-moon':'Full Moon Gift Packages','little':'Little Series','strawberry':'Strawberry Flavour','animal':'The Animal Series','vanilla':'Vanilla Flavour','wedding':'Wedding Gift Packages','croissant':'Croissants','danish':'Danish Pastries','tart':'Tarts','puff':'Puff Pastry','chocolatechip':'Chocolate Chip Cookies','butter':'Butter Cookies','oatmeal':'Oatmeal Cookies','special':'Special Cookies'};
            activeCategory.textContent = currentSubCategory !== 'all' ? (subNames[currentSubCategory] || 'Products') : (subNames['all'] || 'Products');
        }

        // 在 menu.js 中找到这个函数并修改
function viewProductDetails(productId) {
    addToRecentlyViewed(productId);
    // 修改这里：跳转到 php 页面，并把 ID 传过去
    window.location.href = 'product_detail.php?id=' + productId;
}

        // Quick view product in modal
        function quickViewProduct(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            addToRecentlyViewed(productId);
            const isFavorite = favorites.includes(product.id);
            quickViewContent.innerHTML = `
                <button class="close-modal" id="closeModal">×</button>
                <div style="display: flex; gap: 30px; padding: 30px;">
                    <div style="flex: 1;">
                        <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 10px;">
                    </div>
                    <div style="flex: 1;">
                        <h2 style="margin-bottom: 15px; color: #5a3921;">${product.name}</h2>
                        <p style="font-size: 24px; color: #d4a76a; font-weight: bold; margin-bottom: 15px;">RM ${product.price.toFixed(2)}</p>
                        <div style="display: flex; align-items: center; margin-bottom: 15px;"><span class="stars">${'★'.repeat(Math.floor(product.rating||0))}☆</span><span style="margin-left: 10px;">${product.rating || ''} (${product.reviewCount || 0} reviews)</span></div>
                        <p style="margin-bottom: 20px; line-height: 1.6;">${product.fullDescription || product.description || ''}</p>
                        <div style="margin-bottom: 20px;"><strong>Ingredients:</strong> ${product.ingredients || ''}</div>
                        <div style="margin-bottom: 20px;"><strong>Weight:</strong> ${product.weight || ''}</div>
                        <div style="margin-bottom: 20px;"><strong>Allergens:</strong> ${product.allergens || ''}</div>
                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button class="add-to-cart-btn" data-id="${product.id}" style="background: #d4a76a; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; flex: 2;">Add to Cart</button>
                            <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${product.id}" style="background: #f5f5f5; border: 1px solid #ddd; padding: 12px; border-radius: 5px; cursor: pointer;">${isFavorite ? '❤️' : '🤍'}</button>
                        </div>
                    </div>
                </div>
            `;
            quickViewModal.style.display = 'flex';
            document.getElementById('closeModal').addEventListener('click', closeQuickView);
            quickViewModal.addEventListener('click', (e) => { if (e.target === quickViewModal) closeQuickView(); });
            const addToCartBtn = quickViewContent.querySelector('.add-to-cart-btn');
            const favoriteBtn = quickViewContent.querySelector('.favorite-btn');
            addToCartBtn.addEventListener('click', () => { addToCart(product.id, 1); closeQuickView(); });
            favoriteBtn.addEventListener('click', () => { toggleFavorite(product.id); favoriteBtn.innerHTML = favorites.includes(product.id) ? '❤️' : '🤍'; favoriteBtn.classList.toggle('active'); });
        }

        function closeQuickView() { quickViewModal.style.display = 'none'; }

        // Recently viewed management (keep 5 most recent by default)
        function addToRecentlyViewed(productId) {
            recentlyViewed = recentlyViewed.filter(id => id !== productId);
            recentlyViewed.unshift(productId);
            recentlyViewed = recentlyViewed.slice(0, 5);
            localStorage.setItem('bakeryRecentlyViewed', JSON.stringify(recentlyViewed));
            loadRecentlyViewed();
        }

        function loadRecentlyViewed() {
            if (!recentlyViewed || recentlyViewed.length === 0) {
                recentlyViewedSection.style.display = 'none';
                return;
            }
            recentProductsContainer.innerHTML = '';
            recentlyViewed.forEach(pid => {
                const p = products.find(x => x.id === pid);
                if (p) {
                    recentProductsContainer.innerHTML += `<div class="recent-product-card" data-id="${p.id}"><img src="${p.image}" alt="${p.name}" class="recent-product-image"><h4>${p.name}</h4><p style="color:#d4a76a;font-weight:bold;margin:5px 0;">RM ${p.price.toFixed(2)}</p></div>`;
                }
            });
            recentlyViewedSection.style.display = 'block';
            document.querySelectorAll('.recent-product-card').forEach(card => { card.addEventListener('click', function(){ const id = parseInt(this.getAttribute('data-id')); viewProductDetails(id); }); });
        }

        // Toggle favorite
        function toggleFavorite(productId) {
            if (favorites.includes(productId)) favorites = favorites.filter(id => id !== productId);
            else favorites.push(productId);
            localStorage.setItem('bakeryFavorites', JSON.stringify(favorites));
            showToast(favorites.includes(productId) ? 'Added to favorites!' : 'Removed from favorites');
        }

        // Add to cart
        function addToCart(productId, quantity = 1) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            const existing = cart.find(i => i.id === productId);
            if (existing) existing.quantity += quantity;
            else cart.push({ id: product.id, name: product.name, price: product.price, image: product.image, quantity });
            localStorage.setItem('bakeryCart', JSON.stringify(cart));
            updateCartCount();
            showToast(`${product.name} added to cart!`);
        }

        function updateCartCount() {
    // 1. 计算购物车总数
    const total = cart.reduce((s, i) => s + i.quantity, 0);
    
    // 2. 将数量同步到 localStorage，这样跳转到其他页面（如 cart.php）时 header 能读到最新值
    localStorage.setItem('cartItemCount', total.toString());

    // 3. 更新当前页面 header 里的红色数字
    // 注意：header.php 中定义的类名是 .cart-count
    const cartCountElement = document.querySelector('.cart-count');
    
    if (cartCountElement) {
        cartCountElement.textContent = total;
    } else {
        console.warn('未找到 .cart-count 元素，请检查 header.php 是否包含该类名');
    }
}

        function showToast(msg) {
            toast.textContent = msg;
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 2500);
        }

        // Setup product event listeners (delegated attachments after render)
        function setupProductEventListeners() {
            // view-details
            document.querySelectorAll('.view-details-btn').forEach(btn => btn.addEventListener('click', (e) => { e.stopPropagation(); const id = parseInt(btn.getAttribute('data-id')); viewProductDetails(id); }));

            // favorites
            document.querySelectorAll('.favorite-btn').forEach(btn => btn.addEventListener('click', (e) => { e.stopPropagation(); const id = parseInt(btn.getAttribute('data-id')); toggleFavorite(id); btn.innerHTML = favorites.includes(id) ? '❤️' : '🤍'; btn.classList.toggle('active'); }));

            // product-card click -> quick view
            document.querySelectorAll('.product-card').forEach(card => card.addEventListener('click', function(e) { if (!e.target.closest('.favorite-btn') && !e.target.closest('.view-details-btn')) { const id = parseInt(this.getAttribute('data-id')); quickViewProduct(id); } }));
        }

        // Setup event listeners for controls and categories
        function setupEventListeners() {

    // ✅ 一定要先拿 DOM
    const cartIcon = document.querySelector('.cart-icon-wrapper');

    // ✅ 一定要防 null
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.php';
        });
    }




            document.querySelectorAll('.category-main').forEach(btn => btn.addEventListener('click', function(){
                const category = this.getAttribute('data-category');
                document.querySelectorAll('.category-main').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const arrow = this.querySelector('.category-arrow');
                if (arrow) arrow.classList.toggle('active');
                const sub = this.nextElementSibling;
                if (sub) sub.classList.toggle('active');
                if (category !== currentCategory) {
                    currentCategory = category;
                    currentSubCategory = 'all';
                    document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                    const allSub = this.nextElementSibling.querySelector('.subcategory-item[data-subcategory=\"all\"]');
                    if (allSub) allSub.classList.add('active');
                    currentPage = 1;
                    updateActiveCategory();
                    renderProducts();
                }
            }));

            document.querySelectorAll('.subcategory-item').forEach(item => item.addEventListener('click', function(e){
                e.preventDefault();
                document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                currentSubCategory = this.getAttribute('data-subcategory');
                currentPage = 1;
                updateActiveCategory();
                renderProducts();
            }));

            searchBtn.addEventListener('click', () => { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); });
            searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); } });

            sortSelect.addEventListener('change', () => { currentSort = sortSelect.value; currentPage = 1; renderProducts(); });

            prevPageBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderProducts(); } });
            nextPageBtn.addEventListener('click', () => {
                // compute max page based on current filters to avoid exceeding
                const total = filterProducts().length;
                const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
                if (currentPage < maxPage) { currentPage++; renderProducts(); }
            });

            backToTop.addEventListener('click', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); });
            window.addEventListener('scroll', () => { backToTop.style.display = window.pageYOffset > 300 ? 'block' : 'none'; });
        }

        // Initialize after DOM loaded
        // document.addEventListener('DOMContentLoaded', initPage);
		
		initPage();
});




