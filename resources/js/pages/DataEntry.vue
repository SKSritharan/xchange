<template>
    <main class="container mx-auto pt-24 pb-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Data Entry</h1>
                    <p class="text-muted-foreground mt-1">
                        Add new exchange rate data to the system
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <DataEntryForm
                        :currencies="availableCurrencies"
                        :base-currency="BASE_CURRENCY"
                    />
                </div>

                <div class="space-y-6">
                    <div class="card-glass p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                                <Database :size="20" class="text-primary"/>
                            </div>
                            <h3 class="font-semibold">Data Guidelines</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li class="flex items-start">
                  <span class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 shrink-0 mt-0.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                  </span>
                                <span>Enter the midpoint rate for the currency pair</span>
                            </li>
                            <li class="flex items-start">
                  <span class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 shrink-0 mt-0.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                  </span>
                                <span>Use four decimal places for precision</span>
                            </li>
                            <li class="flex items-start">
                  <span class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 shrink-0 mt-0.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                  </span>
                                <span>Rates will be published immediately</span>
                            </li>
                        </ul>
                    </div>

                    <div class="card-glass p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-secondary/10 flex items-center justify-center mr-3">
                                <Shield :size="20" class="text-secondary"/>
                            </div>
                            <h3 class="font-semibold">Access Control</h3>
                        </div>
                        <p class="text-sm text-muted-foreground mb-4">
                            Only authenticated users can submit exchange rate data. All submissions are logged for audit
                            purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import {onMounted} from 'vue';
import {useRouter} from 'vue-router';
import {Database, Shield} from 'lucide-vue-next';
import {useAuth} from '../hooks/useAuth';
import {useToast} from '../hooks/useToast';
import DataEntryForm from '../components/DataEntryForm.vue';

// Sample currencies
const availableCurrencies = ['LKR', 'AUD', 'CAD', 'GBP'];
const BASE_CURRENCY = 'USD';

const {isAuthenticated} = useAuth();
const {toast} = useToast();
const router = useRouter();

// Redirect if not authenticated
// onMounted(() => {
//     if (!isAuthenticated.value) {
//         toast({
//             title: 'Authentication required',
//             description: 'Please login to access this page',
//             variant: 'destructive',
//         });
//         router.push('/login');
//     }
// });
</script>
