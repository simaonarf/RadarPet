<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>RadarPet</title>
</head>

<body>


    <main class="relative">
        <div class="relative inset-0 h-[20vh] flex items-center bg-custom-gradient2 z-10"></div>

        <div class="absolute w-full top-[15vh] z-20 px-8">
            <div class="max-w-[400px] mx-auto p-6 py-8 bg-white shadow-lg rounded-md">
                <h5 class="my-2 text-xl font-semibold text-black text-center">Cadastrar</h5>

                <form action="create.php" method="POST">

                    <div class="mb-4 mt-3">
                        <label for="email" class="text-black">E-mail</label>
                        <input id="email" type="email" required
                            class="w-full mt-3 py-2 px-3 h-10 bg-transparent rounded outline-none border border-gray-200 focus:border-blue-400 focus:ring-0"
                            placeholder="Seu E-mail">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="text-black">Senha</label>
                        <input id="password" type="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="A senha deve ter no mínimo 8 caracteres, com pelo menos uma letra maiúscula, uma letra minúscula e um número."
                            class="w-full mt-3 py-2 px-3 h-10 bg-transparent rounded outline-none border border-gray-200 focus:border-blue-400 focus:ring-0"
                            placeholder="Senha">
                    </div>

                    <div class="mb-4">
                        <label for="confirm-password" class="text-black">Confirmar Senha</label>
                        <input id="confirm-password" type="password" required
                            class="w-full mt-3 py-2 px-3 h-10 bg-transparent rounded outline-none border border-gray-200 focus:border-blue-400 focus:ring-0"
                            placeholder="Confirmar Senha">
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="default-checkbox" type="checkbox" value="" required
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-checkbox" class="ml-3 text-sm font-medium text-gray-900">Li e concordo com
                            os <a href="#" class="text-blue-600">Termos de Privacidade</a>.</label>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="submit-btn" disabled class="h-[50px] px-6 mt-3 bg-blue-500 text-white text-sm font-semibold rounded-full shadow-md hover:bg-blue-600
                            disabled:opacity-50 disabled:cursor-not-allowed">
                            Confirmar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</body>

</html>