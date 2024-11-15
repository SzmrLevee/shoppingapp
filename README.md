# Bevásárlólista Program

## Projektről

Ez a projekt egy egyszerű és könnyen használható **bejelentkezés-alapú bevásárlólista-kezelő alkalmazás**, amely lehetővé teszi a felhasználók számára, hogy listákat hozzanak létre, kezeljenek, és nyomon kövessenek.  
Az adminisztrációs felület biztosítja a felhasználók kezelését, beleértve a státuszuk (aktív, engedélyre vár, tiltott, admin) változtatását.

---

## Funkciók

### 1. **Felhasználói hitelesítés**
- **Bejelentkezési rendszer:** Felhasználónév és jelszó alapú védelem.
- **Felhasználói regisztráció:** Lehetőség új felhasználók létrehozására.
- **Adminisztrációs felület:** Csak az adminisztrátorok számára elérhető funkciók.

### 2. **Bevásárlólista-kezelés**
- **Termékek hozzáadása:** Egyszerűen felvehetők új termékek a listára.
- **Termékek jelölése:** A már megvásárolt tételek egyetlen kattintással jelölhetők.
- **Tételek törlése:** Nem szükséges elemek könnyen eltávolíthatók a listából.

### 3. **Adminisztrációs felület**
Az adminisztrátorok módosíthatják a felhasználók státuszát:
- **Aktív:** Hozzáféréssel rendelkező felhasználók.
- **Engedélyre vár:** Újonnan regisztrált, admin jóváhagyásra váró felhasználók.
- **Tiltott:** Felfüggesztett hozzáféréssel rendelkező felhasználók.
- **Admin:** Teljes jogkörrel rendelkező felhasználók.

---

## Technológiai háttér

A projekt az alábbi technológiákra épül:
- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Adatbázis:** MySQL
