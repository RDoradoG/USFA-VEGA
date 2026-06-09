<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, User } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const { props } = usePage();
const user = props.auth.user;

const isJefe = user?.rol === 'JEFE';

const mainNavItems: NavItem[] = [
    {
        title: 'Leads',
        href: '/leads',
        icon: LayoutGrid,
        show: true
    },
    {
        title: 'Users',
        href: '/users',
        icon: User,
        show: isJefe
    }
];

/*const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];*/
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="'leads'">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!--NavFooter :items="footerNavItems" /-->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
