# GUÍA DE REFERENCIA 03: Fichas Visuales de la Derivada
**Módulo:** Cálculo Diferencial | **Nivel:** Inicial

---

## 1. La Derivada como "Razón de Cambio Instantáneo"
En ciencias e ingeniería, no basta con saber dónde está un objeto; necesitamos saber **qué tan rápido está cambiando** en un instante exacto. La derivada es la herramienta matemática que mide la velocidad en la que ocurre un cambio.

*   **Sin derivada:** Velocidad promedio (Recta Secante).
*   **Con derivada:** Velocidad instantánea (Recta Tangente).

---

## 2. El Truco Lógico (Distancia Cero)
La fórmula de la derivada nace de la fórmula básica de la pendiente de una recta: $m = \frac{y_2 - y_1}{x_2 - x_1}$. 

El truco de la derivada consiste en acercar dos puntos tanto hasta que la distancia entre ellos ($h$) sea microscópica, es decir, el límite cuando $h \to 0$.

$$f'(x) = \lim_{h \to 0} \frac{f(x+h) - f(x)}{h}$$

---

## 3. Relación Gráfica (Lectura de la Pendiente)
Si observas la gráfica de una función $f(x)$, la derivada $f'(x)$ te dice qué está haciendo el sistema gráficamente mediante el signo de su resultado:

| Estado de la Derivada | Comportamiento del Gráfico | Interpretación Física |
| :---: | :--- | :--- |
| **$f'(x) > 0$ (Positiva)** | La curva está **subiendo** (creciente). | Velocidad de aumento (aceleración). |
| **$f'(x) < 0$ (Negativa)** | La curva está **bajando** (decreciente). | Velocidad de pérdida (frenado). |
| **$f'(x) = 0$ (Cero)** | La curva está **plana** (horizontal). | Punto de retorno (Máximo o Mínimo). |

> **Tip de Análisis Visual:** Imagina la derivada como una persona caminando sobre la curva de izquierda a derecha. Si sube una montaña, se cansa (esfuerzo positivo). Si baja, se relaja (esfuerzo negativo). Si está en la cima, el terreno es plano (pendiente cero).