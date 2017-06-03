Title: Understanding The Memory
Date: 2015-06-26 01:11
Modified: 2017-02-16 23:44
Category: Computers
Tags: code, computer, memory
Slug: understanding-the-memory
Authors: Romain Pellerin
Summary: Understanding how memory works

Understanding memory management can be a bit difficult for beginners. However, it's still one of the most important things every developer should know. You'll need to know how it works to write memory-efficient code, to avoid stack overflows. This is especially true when developing for mobile platforms that have very limited memory. Today's cheapest smartphones have an average of 512MB memory. Android will kill any app that consumes too much memory with even warning you. So be careful beforing allocating too many objects.

Nows, let's have a look at memory and let's try to understand how it works.

# Processes and threads

Basically, when a program is launched, the operating system grants it a certain space in memory. The allocated memory contains **four main areas**:

- **Data**: contains the global and static variables (explicitly initialized with a non-zero or non-NULL value, according to [Wikipedia](https://en.wikipedia.org/wiki/Data_segment)) used by the program. The content of constants is on the data segment, whereas references to constants are on the code (see next bullet point). The BSS segment (which is usually adjacent to the data segment) contains all global variables and static variables that are initialized to zero or do not have explicit initialization (in such a case, they [are implicitly initialized to 0](http://stackoverflow.com/questions/13251083/the-initialization-of-static-variables-in-c)). The BSS segment can be considered a part of the data segment.
- **Code segment**: contains the assembly code of the program to be executed.
- **Heap**: contains all dynamically allocated primitive data types or objects (with ```malloc``` in C or ```new``` in C++, for instance). The developer is in charge of the lifetime of these variables, he has to explicitly deallocate memory (with ```free``` in C or ```delete``` in C++).
- **Stack**: it's a LIFO structure. It basically contains all variables being declared inside functions. Every time you enter in a function, a stack frame is created for it. Every time you pass arguments by values to these functions, the arguments are copied to the stack. If you pass references or pointers, their content (an address) is also copied in order to be passed to the function. All the variables on the stack live only inside the function. When the function returns, they are destroyed and the corresponding allocated memory is freed. **Stack values only exist within the scope of the function they are created in.**

<figure class="center">
<img src="{filename}/images/memory-stack.jpg" alt="The stack" />
<figcaption>Image taken from <a href="http://www.linuxjournal.com/article/6391">http://www.linuxjournal.com/article/6391</a></figcaption>
</figure>

    :::c
    int add(int a, int b) {
        return a + b;
    }

    int main() {
        int c = add(1,2);
        return 0;
    }

Let's see how this code gets compiled in x86 assembly.

    :::bash
    gcc -S -masm=intel main.c -fno-asynchronous-unwind-tables -o output.asm

Now, we'll see the instructions using the stack.

    :::assembly
        .file	"main.c"
        .intel_syntax noprefix
        .text
        .globl	add
        .type	add, @function
    add:
        push	rbp
        mov	rbp, rsp
        mov	DWORD PTR [rbp-4], edi
        mov	DWORD PTR [rbp-8], esi
        mov	edx, DWORD PTR [rbp-4]
        mov	eax, DWORD PTR [rbp-8]
        add	eax, edx
        pop	rbp
        ret
        .size	add, .-add
        .globl	main
        .type	main, @function
    main:
        push	rbp
        mov	rbp, rsp
        sub	rsp, 16
        mov	esi, 2
        mov	edi, 1
        call	add
        mov	DWORD PTR [rbp-4], eax
        mov	eax, 0
        leave
        ret
        .size	main, .-main
        .ident	"GCC: (Ubuntu 5.4.0-6ubuntu1~16.04.4) 5.4.0 20160609"
        .section    .note.GNU-stack,"",@progbits

Threads can be seen as sub-processes, although they belong to a specific process. Thus, they share the same virtual space (same data segment, etc). Only one thing is unique to each thread: they all have their own stack.

# Allocating memory for objects and primitive data types

## C++

Every dynamic allocation goes to the heap. The rest on the stack. So every left handside part of the following lines (what is on the left of the equal sign) is on the stack.

Global variables and static variables (not only global) go to the data segment.

Here are some examples, to make things clear:

    :::c++
    void fonction() {
      Object o(); // On the stack
      Object o2 = Object(); // On the stack
      Object& ref = o; // The reference is on the stack
      Object* pt = &o; // The pointer is on the stack
      Object* pt2 = new Object(); /* The right handside (the actual object)
        is on the heap, the left handside is on the stack */

      /* Everything that has been allocated on the stack will be
        deleted at the end of the function */
    }

To delete what is on the heap, you need to use the ```delete``` keyword.

<figure class="center">
<img alt="Pointers and addresses" src="{filename}/images/memory_cpp_example.png" />
</figure>

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/CSVRA4_xOkw?rel=0" frameborder="0" allowfullscreen></iframe>

## Java

The main difference is that objects on the heap are automatically destroyed by the garbage collector, once they are no longer referenced. Moreover, only primitives are on the stack. All the objects are allocated on the heap. However, references (which are addresses) are passed as arguments to functions on the stack.

That's all folks!

# Further reading

- [7. Memory : Stack vs Heap](http://gribblelab.org/CBootcamp/7_Memory_Stack_vs_Heap.html)
- [The difference between pointers and arrays](http://www.cplusplus.com/forum/articles/9/)
- [The memory models that underlie programming languages](http://canonical.org/~kragen/memory-models/)
- [Hack the Virtual Memory: malloc, the heap & the program break](https://blog.holbertonschool.com/hack-the-virtual-memory-malloc-the-heap-the-program-break/)
