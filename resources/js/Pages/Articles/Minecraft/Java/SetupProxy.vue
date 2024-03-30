<script setup>
import ArticleTemplate from "@/Components/ArticleTemplate.vue";
import proxy_scheme from "@pngs/articles/minecraft/java/proxy/proxy_scheme.png";
import step1 from "@pngs/articles/minecraft/java/proxy/step1.png";
import CodeBlock from "@/Components/CodeBlock.vue";
</script>

<template>
    <ArticleTemplate>
        <template #title>Как настроить Minecraft Proxy?</template>
        <template #content>
            <span
                >Minecraft Proxy - это технология, позволяющая реализовать хаб на вашем сервере. Проще говоря, с её
                помощью вы можете создать шлюз между серверами и перемещаться по ним через порталы, как если бы вы
                телепортировались в Ад или Энд.</span
            >
            <v-lazy-image
                :src="proxy_scheme"
                alt="proxy scheme" />
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 1: </span>Приобретите Minecraft-сервер, выбрав при создании
                Proxy в типе сервера.</span
            >
            <v-lazy-image
                :src="step1"
                alt="step1" />
            <span class="border-2 p-4 border-wings-light"
                ><span class="font-['OpenSans-Bold']">Совет:</span> если вы новичок, советуем выбирать BungeeCord, так
                как это самое простое в освоении Proxy-ядро, по которому написано много статей и плагинов.</span
            >

            <span
                ><span class="font-['OpenSans-Bold']">Шаг 2: </span>Перейдите в панель и найдите в файлах сервера
                <span class="techtext">config.yml</span></span
            >

            <span class="sm:ml-10"
                ><span class="font-['OpenSans-Bold']">Шаг 2.1: </span>Если ваш сервер пиратский, замените в строке
                <span class="techtext">online_mode</span> <span class="techtext">true</span> на
                <span class="techtext">false</span> для отключения проверки лицензии.</span
            >
            <CodeBlock class="sm:ml-10 sm:text-base text-xs">
                online_mode: <span class="text-pink-600">false</span>
            </CodeBlock>

            <span class="sm:ml-10"
                ><span class="font-['OpenSans-Bold']">Шаг 2.2: </span>Найдите блок
                <span class="techtext">servers</span> и укажите в нём сервера, которые хотите подключить к
                прокси-хабу.</span
            >
            <CodeBlock class="sm:ml-10 sm:text-base text-xs">
                servers:<br />
                <div class="ml-6">
                    lobby:<br />
                    <div class="ml-6">
                        motd: <span class="text-amber-500">'Lobby'</span><br />
                        address: <span class="text-violet-500">228.228.228.228:25558</span><br />
                        restricted: <span class="text-pink-600">false</span><br />
                    </div>
                    anarchy:<br />
                    <div class="ml-6">
                        motd: <span class="text-amber-500">'Anarchy'</span><br />
                        address: <span class="text-violet-500">123.321.123.321:25557</span><br />
                        restricted: <span class="text-pink-600">false</span><br />
                    </div>
                    shkoloserver:<br />
                    <div class="ml-6">
                        motd: <span class="text-amber-500">'Shkoloserver'</span><br />
                        address: <span class="text-violet-500">111.111.111.111:25556</span><br />
                        restricted: <span class="text-pink-600">false</span><br />
                    </div>
                </div>
            </CodeBlock>

            <span class="sm:ml-10"
                ><span class="font-['OpenSans-Bold']">Шаг 2.3: </span>Найдите блок
                <span class="techtext">priorities</span> и укажите в нём сервер, на который игрок должен попадать при
                заходе.</span
            >

            <CodeBlock class="sm:ml-10 sm:text-base text-xs">
                priorities:<br />
                <div class="sm:ml-6">- lobby</div>
            </CodeBlock>

            <span class="sm:ml-10"
                ><span class="font-['OpenSans-Bold']">Шаг 2.4: </span>Найдите параметр
                <span class="techtext">ip_forward</span> и поменяйте в нём значение
                <span class="techtext">false</span> на<span class="techtext">true</span>. Далее откройте файл
                <span class="techtext">spigot.yml</span> на каждом сервере, который хотите подключить к прокси, и
                поменяйте значение параметра <span class="techtext">bungeecord: false</span> на
                <span class="techtext">true</span>.
            </span>
            <CodeBlock class="sm:ml-10 sm:text-base text-xs">
                ip_forward: <span class="text-pink-600">true</span><br />
                bungeecord: <span class="text-pink-600">true</span>
            </CodeBlock>

            <span class="sm:ml-10"
                ><span class="font-['OpenSans-Bold']"
                    >Шаг 2.5: Если требуется, укажите в <span class="techtext">config.yml</span> лимит количества
                    игроков в строке <span class="techtext">max_players</span>.
                </span></span
            >

            <CodeBlock class="sm:ml-10 sm:text-base text-xs">
                max_players: <span class="text-violet-500">200</span>
            </CodeBlock>

            <span class="border-2 p-4 border-red-500"
                ><span class="font-['OpenSans-Bold']">Если ваш сервер атакуют боты или мамкины хакеры, вы можете:</span
                ><br />
                &mdash; Настроить TCPShield
                <br />
                &mdash; Настроить BungeeGuard
                <br />
                &mdash; Включить пункт <span class="techtext">reconnect:</span> в
                <span class="techtext">modules.yml</span>. Это заставит всех заходящих игроков переподключаться при
                первом заходе, чтобы доказать, что они не роботы.<br />
            </span>
        </template>
    </ArticleTemplate>
</template>

<style scoped></style>
