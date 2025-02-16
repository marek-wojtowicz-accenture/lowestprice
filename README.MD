# Magento_LowestPrice

**Magento_LowestPrice** to moduł dla platformy Magento, opracowany w ramach pracy inżynierskiej, który umożliwia automatyczne rejestrowanie i wyświetlanie najniższej ceny produktu z ostatnich 30 dni. Moduł został zaprojektowany z myślą o zgodności z regulacjami UE oraz zwiększeniu przejrzystości oferty sklepu.

## Funkcjonalności
- **Automatyczne rejestrowanie cen**: codzienne zapisywanie najniższej ceny produktu do dedykowanej tabeli.
- **Aktualizacje danych przez zadania cron**: automatyczne usuwanie rekordów starszych niż 30 dni.
- **Integracja z frontendem**: wyświetlanie najniższej ceny na stronach produktów, co zwiększa transparentność oferty.
- **Integracja z Magento**: wykorzystanie standardowych mechanizmów, takich jak dependency injection, modele, resource modele, interfejsy API, repository, pluginy oraz UI Components.

## Instalacja

Aby zainstalować moduł **Magento_LowestPrice** przez Composer, wykonaj następujące kroki w katalogu głównym projektu Magento:


1) `composer require marek-wojtowicz-accenture/lowestprice`

2) `bin/magento setup:upgrade`

3) `bin/magento setup:di:compile`

4) `bin/magento cache:flush`

## Cel modułu

Moduł został stworzony w celach naukowych w ramach pracy inżynierskiej i jest przeznaczony dla małych przedsiębiorstw oraz użytkowników nietechnicznych, którzy chcą samodzielnie zarządzać swoim sklepem e-commerce bez konieczności płacenia abonamentu.

## Licencja

Moduł został opracowany w celach naukowych. W przypadku wykorzystania modułu w projektach komercyjnych proszę o kontakt w celu ustalenia warunków licencyjnych.
