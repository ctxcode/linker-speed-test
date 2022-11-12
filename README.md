
# c link speed: Many small o files vs. Few big o files

Simple test to see which is faster. I dont even know if this test can be compared to real code

## Results

Linker used: `gcc`

```
1000 files, 10 functions -> 1.7s
100 files, 100 functions -> 0.2s
10 files, 1000 functions -> 0.1s
1 files, 10000 functions -> 0.1s
```

## Usage

```sh
php gen.php {object count} {func count per file}
# php gen.php 10 100
```

## Conclusion

many small files is slower than few large files
