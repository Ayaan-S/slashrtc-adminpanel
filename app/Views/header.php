<?php  
$filename = 'logoJson/logo.json'; // Use relative path instead of FCPATH
if (file_exists($filename)) {      
    $data = file_get_contents($filename);     
    $logos = json_decode($data, true); 
} 

// Simulate session data for testing
$user = isset($_SESSION['user']) ? $_SESSION['user'] : (object) ['name' => 'Guest'];
?> 

<!DOCTYPE html> 
<html lang="en">
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
</head> 

<body> 

    <!-- ---------------------------------------Header--first--section---------------------- -->     
    <div class="flex justify-between items-center bg-white p-1 border-b border-gray-400">         
        <div class="">             
            <img class="mix-blend-multiple w-[40px]" src="/slashlogo.png" alt="No Image Found" />         

        </div>         
        <div class="flex justify-between items-center ">             
            <a href="/logout" class="mx-3 "><?= ucfirst($user->name) ?></a>             
            <img class="mx-3 mix-blend-multiple w-[50px] hover:cursor-pointer" src="/userimage.png" alt="No Image Found" />         

        </div>     
    </div>     

    <!-- ---------------------------------------------header--second--section---------------------- -->     
    <div class="flex justify-center items-center bg-white p-1 border-b border-gray-400">         
        <div class="flex flex-wrap justify-center items-center w-full">             
            <?php foreach ($logos as $logo) { ?>                 
                <div class="flex flex-col mx-3 p-2 cursor-pointer" id="<?= $logo['name']; ?>" onmouseover="showSubElement('<?= $logo['name']; ?>')" onmouseleave="hideSubElement('<?= $logo['name']; ?>')">                     
                    <div class="flex">                         
                        <p class="m-2 text-black"><i class="<?= $logo['img']; ?>"></i></p>                         
                        <p class="m-2 text-gray-500"><?= $logo['name']; ?></p>                     
                    </div>                     
                    <div class="absolute flex flex-col hidden bg-white shadow-lg rounded-lg border border-gray-600" id="<?= $logo['name']; ?>1">                         
                        <?php foreach ($logo['subElement'] as $index => $subElement) { ?>                             
                            <a href="<?= $logo['link'][$index] ?>" class="m-2 text-gray-500 border-b border-gray-400 p-2"><?= $subElement; ?></a>                         
                        <?php } ?>                     
                    </div>                 
                </div>             
            <?php } ?>         
        </div>     
    </div>       

    <!-- ----------------------------------------------header--third--section---------------------- -->     
    <div class="flex justify-end items-center bg-gray-100 p-2 pr-5 border-b border-gray-400 shadow-xl">         
        <button class="py-2.5 px-12 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 z--99" onclick="openFilterModal()">Filter</button>                         

        <select class="rounded-2xl p-2 w-[250px] m-1">             
            <option value="">All Campaigns</option>             
            <option value="option1">Option 1</option>             
            <option value="option2">Option 2</option>         
        </select>         

        <select class="rounded-2xl p-2 w-[250px] m-1">             
            <option value="">All Process</option>             
            <option value="option1">Option 1</option>             
            <option value="option2">Option 2</option>         
        </select>         

        <input type="datetime-local" class="rounded-2xl p-2 w-[250px] m-1" />                                       
        <button class="bg-white rounded-full p-2 m-1 border border-gray-300">Go</button>         
        <button class="bg-white rounded-full p-2 m-1 border border-gray-300">             
            <i class="fa-solid fa-download"></i>         
        </button>     
    </div>     

    <!-- -----------------------------------------Filter Modal------------------------------------ -->
    <div id="filterModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden z-50">
        <div class="flex justify-center items-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg p-8 w-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Filter Options</h2>
                    <button class="text-gray-500" onclick="closeFilterModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="filterCampaigns" class="block text-gray-700">Input</label>
                        <input type="text" id="filterCampaigns" class="w-full p-2 rounded-xl border border-gray-300">
                    </div>

                    <div>
                        <label for="filterProcess" class="block text-gray-700">Roles</label>
                        <select id="filterProcess" class="w-full p-2 rounded-xl border border-gray-300">
                            <option value="">All Roles</option>
                            <option value="option1">Admin</option>
                            <option value="option1">Supervisor</option>
                            <option value="option1">Team Lead</option>
                            <option value="option1">Agent</option>
                        </select>
                    </div>

                    <div>
                        <label for="filterDate" class="block text-gray-700">Date</label>
                        <input type="datetime-local" id="filterDate" class="w-full p-2 rounded-xl border border-gray-300" />
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-xl" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- -----------------------------------------Script--------------------------------------------------------- --> 
    <script> 
        function showSubElement(event) {             
            document.getElementById(event + "1").classList.remove('hidden')          
        }          

        function hideSubElement(event) {             
            document.getElementById(event + "1").classList.add('hidden')          
        }

        // Open Filter Modal
        function openFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
        }

        // Close Filter Modal
        function closeFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
        }

        // Apply Filters (for now just close modal as a placeholder)
        function applyFilters() {
            // You can handle the filter application here (e.g., sending the selected filter values to the server)
            closeFilterModal();
        }
    </script> 
</body> 
</html>
