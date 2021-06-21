#!/usr/bin/env python3
import random
from tkinter import *
from datetime import datetime

window = Tk()

window.configure(background="black", cursor="none")
window.attributes("-fullscreen", True)

window.title("Time")

lbl0 = Label(window, fg="white", bg="black")
lbl0.place(x=random.randrange(0, 1920 - 1046, 1), y=random.randrange(0, 1080 - 347, 1))

lbl = Label(window, text=datetime.now().strftime("%H:%M:%S"), font=("Arial Bold", 200), fg="white", bg="black")
lbl.pack(in_=lbl0)

lbl2 = Label(window, text=datetime.now().strftime("%d.%m.%Y"), font=("Arial Bold", 20), fg="white", bg="black")
lbl2.pack(in_=lbl0)

xAdd = [-1, 1][random.random() > 0.5]
yAdd = [-1, 1][random.random() > 0.5]


def tick():
    global xAdd, yAdd
    lbl.after(1000, tick)
    if (window.winfo_width() < 10) :
        return
    lbl.config(text=datetime.now().strftime("%H:%M:%S"))
    lbl2.config(text=datetime.now().strftime("%d.%m.%Y"))
    lbl0.place(x=lbl0.winfo_x() + xAdd, y=lbl0.winfo_y() + yAdd)
    if (lbl0.winfo_x() <= 0) :
        xAdd = 1
    if (window.winfo_width() - lbl0.winfo_width() - lbl0.winfo_x() <= 0):
        xAdd = -1
    if (lbl0.winfo_y() <= 0) :
        yAdd = 1
    if (window.winfo_height() - lbl0.winfo_height() - lbl0.winfo_y() <= 0):
        yAdd = -1
    print("Window: {}x{}; Label: {}x{}; XY: {}x{}; DIR: {}x{};".format(window.winfo_width(), window.winfo_height(), lbl0.winfo_width(), lbl0.winfo_height(),lbl0.winfo_x(), lbl0.winfo_y(), xAdd, yAdd))


tick()

window.mainloop()


